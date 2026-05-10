# BeanChilling WordPress Theme — Elementor Edition

## Theme Structure

```
beanchilling-theme/
├── style.css                  # Theme metadata (required by WP)
├── functions.php              # Theme setup, Elementor support, enqueue
├── header.php                 # Site header + navigation
├── footer.php                 # Site footer
├── index.php                  # Fallback template
├── page.php                   # Default page template (Elementor-editable)
├── front-page.php             # Home page (default content + Elementor override)
├── single.php                 # Blog post template
├── 404.php                    # 404 error page
├── elementor-fullwidth.php    # Full-width template (with header/footer)
├── elementor-canvas.php       # Blank canvas (no header/footer)
├── assets/
│   ├── css/
│   │   └── main.css           # All original BeanChilling styles
│   └── images/
│       ├── coffee_farm_main_bg.png
│       ├── 9349234.jpg
│       └── hands-holding-coffee-beans.jpg
└── README.md                  # This file
```

## Installation

1. **Upload the theme**
   - Zip the `beanchilling-theme` folder
   - In WordPress Admin → **Appearance → Themes → Add New → Upload Theme**
   - Upload the zip and activate

2. **Install Elementor**
   - Go to **Plugins → Add New** → search for **Elementor**
   - Install and activate **Elementor Website Builder**
   - (Optional) Install **Elementor Pro** for header/footer builder

3. **Create your pages**
   Create these WordPress pages (if they don't exist):
   - **Home** — Set as your static front page in **Settings → Reading**
   - **Team Zenith** (slug: `team-zenith`)
   - **IMRAD** (slug: `imrad`)
   - **App Showcase** (slug: `app-showcase`)

4. **Set up the menu**
   - Go to **Appearance → Menus**
   - Create a new menu, add your 4 pages
   - Assign it to the **Primary Menu** location

5. **Set the homepage**
   - Go to **Settings → Reading**
   - Select **A static page** → set **Homepage** to your "Home" page

## Editing Pages with Elementor

1. Go to **Pages → All Pages**
2. Hover over any page and click **Edit with Elementor**
3. Use Elementor's drag-and-drop editor to build your content
4. The theme's dark coffee styling is automatically applied

### Recommended Page Templates

| Page | Template to Use |
|------|-----------------|
| Home | Default (front-page.php auto-applies) |
| Team Zenith | Elementor Full Width |
| IMRAD | Elementor Full Width |
| App Showcase | Elementor Full Width |

### Template Options

- **Default** — Includes header + footer + page-wrap container
- **Elementor Full Width** — Header + footer, no container (full-width sections)
- **Elementor Canvas** — Completely blank (design everything in Elementor)

## Theme Features

- **Dark coffee-themed design** with warm accent colors
- **Fraunces + Sora** font pairing (auto-loaded)
- **Custom navigation walker** with `aria-current="page"` support
- **Custom Logo** support (set in Appearance → Customize)
- **Elementor Pro locations** registered (header + footer)
- **Footer widget area** for custom footer content
- **Responsive** — works across all device sizes
- **Page-specific body classes** — `page-home`, `page-{slug}` for custom CSS per page

## Elementor Design Tips

To match the original BeanChilling design in Elementor:

### Colors (use these in Elementor Global Colors)
- **Background**: `#130d08` (darkest) to `#4c3a25` (lighter)
- **Text Main**: `#f5f4f0`
- **Accent / Gold**: `#d6b37a`
- **Text Soft**: `rgba(245, 244, 240, 0.82)`
- **Panel BG**: `rgba(255, 255, 255, 0.06)`
- **Panel Border**: `rgba(255, 255, 255, 0.16)`

### Fonts (use these in Elementor Global Fonts)
- **Headings**: Fraunces, weight 600-700
- **Body**: Sora, weight 400-600

### Section Styling
- Set section background to **transparent** (body gradient provides the background)
- Use **Inner Section** width: 1100px max-width for content containment
- Border radius on cards: `0.95rem`
- Button style: pill shape (`border-radius: 999px`), gold background

## Using Elementor Pro (Optional)

If you have Elementor Pro, you can also:
- Build **custom header/footer** in Theme Builder (the theme registers locations)
- Create **dynamic templates** for archives and single posts
- Use **Theme Builder conditions** for page-specific headers

The theme automatically detects Elementor Pro and delegates header/footer rendering when Pro templates are active.
