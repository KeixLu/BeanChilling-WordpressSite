<?php
/**
 * BeanChilling AI Chatbot
 *
 * Powered by Google Gemini via Vertex AI Express (aiplatform.googleapis.com).
 * Uses x-goog-api-key header auth — works from all regions including PH.
 * Requires: define( 'GEMINI_API_KEY', 'AQ...your-key' ) in wp-config.php.
 * Key source: Google AI Studio with vertexai=True (AQ. prefix key)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BeanChilling_Chatbot {

	const AJAX_ACTION  = 'beanchilling_chat';
	const NONCE_ACTION = 'bc_chat_nonce';
	const GEMINI_MODEL = 'gemini-3.1-flash-lite-preview';
	const MAX_HISTORY  = 8; // max history items accepted from client

	public function __construct() {
		add_action( 'wp_ajax_' . self::AJAX_ACTION,        array( $this, 'handle_ajax' ) );
		add_action( 'wp_ajax_nopriv_' . self::AJAX_ACTION, array( $this, 'handle_ajax' ) );
		add_action( 'wp_footer', array( $this, 'render_widget' ) );
	}

	// -----------------------------------------------------------------------
	// AJAX handler
	// -----------------------------------------------------------------------

	public function handle_ajax() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );

		$message = sanitize_text_field( wp_unslash( isset( $_POST['message'] ) ? $_POST['message'] : '' ) );
		if ( '' === $message ) {
			wp_send_json_error( array( 'reply' => 'Please enter a message.' ) );
		}

		$api_key = defined( 'GEMINI_API_KEY' ) ? GEMINI_API_KEY : '';
		if ( '' === $api_key ) {
			wp_send_json_error( array( 'reply' => 'The chatbot is not configured yet. Add your Gemini API key to wp-config.php.' ) );
		}

		$contents = $this->build_contents( $message );

		$payload = wp_json_encode( array(
			'system_instruction' => array(
				'parts' => array( array( 'text' => $this->get_system_prompt() ) ),
			),
			'contents'         => $contents,
			'generationConfig' => array(
				'maxOutputTokens' => 350,
				'temperature'     => 0.4,
			),
		) );

		$response = wp_remote_post(
			'https://aiplatform.googleapis.com/v1beta1/publishers/google/models/'
				. self::GEMINI_MODEL
				. ':generateContent',
			array(
				'headers' => array(
					'Content-Type'   => 'application/json',
					'x-goog-api-key' => $api_key,
				),
				'body'    => $payload,
				'timeout' => 15,
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'reply' => 'Connection error. Please try again.' ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! empty( $data['error'] ) ) {
			wp_send_json_error( array( 'reply' => 'API error: ' . esc_html( isset( $data['error']['message'] ) ? $data['error']['message'] : 'Unknown error' ) ) );
		}

		$reply = isset( $data['candidates'][0]['content']['parts'][0]['text'] )
			? $data['candidates'][0]['content']['parts'][0]['text']
			: 'Sorry, I could not generate a response.';

		wp_send_json_success( array( 'reply' => wp_strip_all_tags( $reply ) ) );
	}

	// -----------------------------------------------------------------------
	// Build Gemini contents array (history + current message)
	// -----------------------------------------------------------------------

	private function build_contents( $message ) {
		$contents    = array();
		$history_raw = wp_unslash( isset( $_POST['history'] ) ? $_POST['history'] : '[]' );
		$history     = json_decode( $history_raw, true );

		if ( is_array( $history ) ) {
			$history = array_slice( $history, -self::MAX_HISTORY );
			foreach ( $history as $item ) {
				$role = ( isset( $item['role'] ) && 'model' === $item['role'] ) ? 'model' : 'user';
				$text = sanitize_text_field( isset( $item['text'] ) ? $item['text'] : '' );
				if ( '' !== $text ) {
					$contents[] = array( 'role' => $role, 'parts' => array( array( 'text' => $text ) ) );
				}
			}
		}

		$contents[] = array( 'role' => 'user', 'parts' => array( array( 'text' => $message ) ) );

		return $contents;
	}

	// -----------------------------------------------------------------------
	// Build (or retrieve cached) system prompt enriched with page content
	// -----------------------------------------------------------------------

	private function get_system_prompt() {
		$cached = get_transient( 'bc_chatbot_prompt' );
		if ( false !== $cached ) {
			return $cached;
		}

		$base = 'You are BeanBot, the helpful assistant for the BeanChilling project website. '
			. 'BeanChilling is a GIS-based coffee farm mapping and profiling application developed by Team Zenith. '
			. 'The platform helps coffee producers map their farms and profile their coffees to make better field-level decisions. '
			. 'Answer questions about the project concisely and helpfully based on the context below. '
			. 'If asked about topics unrelated to BeanChilling or coffee farming, politely redirect the user. '
			. 'Keep answers under 4 sentences unless more detail is specifically requested.';

		// Enrich with live page content from the site
		$pages = get_posts( array(
			'post_type'      => array( 'page', 'post' ),
			'post_status'    => 'publish',
			'posts_per_page' => 15,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		) );

		$context = '';
		foreach ( $pages as $page ) {
			$text = wp_strip_all_tags( apply_filters( 'the_content', $page->post_content ) );
			$text = trim( preg_replace( '/\s+/', ' ', $text ) );
			if ( strlen( $text ) > 40 ) {
				$context .= "\n\n[" . $page->post_title . "]\n" . mb_substr( $text, 0, 900 );
			}
		}

		$prompt = $base . ( $context ? "\n\nProject content from the website:\n" . $context : '' );
		set_transient( 'bc_chatbot_prompt', $prompt, HOUR_IN_SECONDS );

		return $prompt;
	}

	// -----------------------------------------------------------------------
	// Render widget HTML + CSS + JS in footer
	// -----------------------------------------------------------------------

	public function render_widget() {
		$nonce    = wp_create_nonce( self::NONCE_ACTION );
		$ajax_url = esc_url( admin_url( 'admin-ajax.php' ) );
		$action   = esc_js( self::AJAX_ACTION );
		$nonce_js = esc_js( $nonce );
		?>
<!-- BeanChilling Chatbot Widget -->
<style id="bc-chatbot-css">
#bc-chatbot *,#bc-chatbot *::before,#bc-chatbot *::after{box-sizing:border-box}
#bc-chatbot{position:fixed;bottom:24px;right:24px;z-index:99999;font-family:'Sora',sans-serif;font-size:14px;line-height:1.5}
#bc-chat-toggle{width:54px;height:54px;border-radius:50%;background:#d6b37a;color:#1f150d;border:none;cursor:pointer;font-size:24px;box-shadow:0 4px 22px rgba(0,0,0,.55);transition:transform .2s,box-shadow .2s;display:flex;align-items:center;justify-content:center;margin-left:auto}
#bc-chat-toggle:hover{transform:scale(1.09);box-shadow:0 6px 30px rgba(214,179,122,.45)}
#bc-chat-toggle:focus-visible{outline:2px solid #d6b37a;outline-offset:3px}
#bc-chat-window{position:absolute;bottom:68px;right:0;width:320px;background:#1c1008;border:1px solid rgba(214,179,122,.3);border-radius:16px;box-shadow:0 18px 52px rgba(0,0,0,.7);display:flex;flex-direction:column;overflow:hidden;animation:bc-up .2s ease}
@keyframes bc-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
#bc-chat-window[hidden]{display:none!important}
#bc-chat-header{background:#d6b37a;color:#1f150d;padding:11px 16px;font-weight:600;display:flex;align-items:center;justify-content:space-between;font-size:14px;gap:8px}
#bc-chat-header-left{display:flex;align-items:center;gap:8px}
.bc-header-badge{font-size:10px;background:rgba(31,21,13,.18);padding:2px 8px;border-radius:20px;font-weight:500;letter-spacing:.03em;text-transform:uppercase}
#bc-chat-close{background:none;border:none;cursor:pointer;color:#1f150d;font-size:16px;line-height:1;padding:2px;opacity:.65;transition:opacity .15s;border-radius:4px}
#bc-chat-close:hover{opacity:1}
#bc-chat-close:focus-visible{outline:2px solid #1f150d;outline-offset:2px}
#bc-chat-messages{flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:8px;max-height:300px;min-height:180px;scrollbar-width:thin;scrollbar-color:rgba(214,179,122,.25) transparent}
#bc-chat-messages::-webkit-scrollbar{width:4px}
#bc-chat-messages::-webkit-scrollbar-track{background:transparent}
#bc-chat-messages::-webkit-scrollbar-thumb{background:rgba(214,179,122,.25);border-radius:4px}
.bc-msg{max-width:88%;padding:8px 13px;border-radius:12px;word-break:break-word;font-size:13px;line-height:1.5}
.bc-msg-bot{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);color:#ffffff;align-self:flex-start;border-bottom-left-radius:3px}
.bc-msg-user{background:rgba(214,179,122,.18);border:1px solid rgba(214,179,122,.28);color:#ffffff;align-self:flex-end;border-bottom-right-radius:3px}
.bc-typing{color:rgba(255,255,255,.5);font-style:italic}
#bc-chat-input-row{display:flex;border-top:1px solid rgba(255,255,255,.08);background:rgba(0,0,0,.25)}
#bc-chat-input{flex:1;background:none;border:none;outline:none;color:#ffffff;padding:10px 12px;font-family:inherit;font-size:13px}
#bc-chat-input::placeholder{color:rgba(255,255,255,.4)}
#bc-chat-send{background:none;border:none;cursor:pointer;color:#d6b37a;padding:10px 14px;font-size:18px;line-height:1;transition:opacity .15s}
#bc-chat-send:hover{opacity:.75}
#bc-chat-send:disabled{opacity:.3;cursor:default}
#bc-chat-send:focus-visible{outline:2px solid #d6b37a;outline-offset:2px}
@media(max-width:380px){#bc-chat-window{width:calc(100vw - 32px);right:-8px}}
</style>

<div id="bc-chatbot">
	<button id="bc-chat-toggle" aria-label="Open BeanBot chat" aria-expanded="false">&#9749;</button>
	<div id="bc-chat-window" hidden>
		<div id="bc-chat-header">
			<div id="bc-chat-header-left">
				<span>BeanBot</span>
				<span class="bc-header-badge">AI</span>
			</div>
			<button id="bc-chat-close" aria-label="Close chat">&#10005;</button>
		</div>
		<div id="bc-chat-messages" role="log" aria-live="polite" aria-label="Chat messages"></div>
		<div id="bc-chat-input-row">
			<input id="bc-chat-input" type="text" placeholder="Ask about BeanChilling&#8230;" autocomplete="off" maxlength="300" aria-label="Type your message" />
			<button id="bc-chat-send" aria-label="Send message">&#10148;</button>
		</div>
	</div>
</div>

<script id="bc-chatbot-js">
(function () {
	'use strict';
	var toggle  = document.getElementById('bc-chat-toggle');
	var win     = document.getElementById('bc-chat-window');
	var closeBtn= document.getElementById('bc-chat-close');
	var msgs    = document.getElementById('bc-chat-messages');
	var inp     = document.getElementById('bc-chat-input');
	var sendBtn = document.getElementById('bc-chat-send');
	var history = [];
	var isOpen  = false;

	function openChat() {
		win.hidden = false;
		toggle.setAttribute('aria-expanded', 'true');
		isOpen = true;
		if (!msgs.children.length) {
			addMsg('bot', 'Hi! I\u2019m BeanBot \u2615 Ask me anything about the BeanChilling project.');
		}
		inp.focus();
	}

	function closeChat() {
		win.hidden = true;
		toggle.setAttribute('aria-expanded', 'false');
		isOpen = false;
	}

	toggle.addEventListener('click', function () { isOpen ? closeChat() : openChat(); });
	closeBtn.addEventListener('click', closeChat);

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && isOpen) closeChat();
	});

	function addMsg(role, text) {
		var d = document.createElement('div');
		d.className = 'bc-msg bc-msg-' + (role === 'bot' ? 'bot' : 'user');
		d.textContent = text;
		msgs.appendChild(d);
		msgs.scrollTop = msgs.scrollHeight;
		return d;
	}

	function showTyping() {
		var d = document.createElement('div');
		d.className = 'bc-msg bc-msg-bot bc-typing';
		d.id = 'bc-typing';
		d.textContent = 'BeanBot is typing\u2026';
		msgs.appendChild(d);
		msgs.scrollTop = msgs.scrollHeight;
	}

	function hideTyping() {
		var t = document.getElementById('bc-typing');
		if (t) t.remove();
	}

	function sendMsg() {
		var text = inp.value.trim();
		if (!text) return;

		addMsg('user', text);
		var historySlice = history.slice(-8);
		history.push({ role: 'user', text: text });
		inp.value = '';
		sendBtn.disabled = true;
		showTyping();

		var fd = new FormData();
		fd.append('action',  '<?php echo $action; ?>');
		fd.append('nonce',   '<?php echo $nonce_js; ?>');
		fd.append('message', text);
		fd.append('history', JSON.stringify(historySlice));

		fetch('<?php echo $ajax_url; ?>', { method: 'POST', body: fd })
			.then(function (r) { return r.json(); })
			.then(function (data) {
				hideTyping();
				var reply = (data.success && data.data && data.data.reply)
					? data.data.reply
					: ((data.data && data.data.reply) ? data.data.reply : 'Sorry, something went wrong.');
				addMsg('bot', reply);
				history.push({ role: 'model', text: reply });
			})
			.catch(function () {
				hideTyping();
				addMsg('bot', 'Connection error. Please try again.');
			})
			.finally(function () {
				sendBtn.disabled = false;
				inp.focus();
			});
	}

	sendBtn.addEventListener('click', sendMsg);
	inp.addEventListener('keydown', function (e) {
		if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
	});
}());
</script>
		<?php
	}
}

new BeanChilling_Chatbot();