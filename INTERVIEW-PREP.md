# Interview Preparation Document

NASA Web Developer Position - Steve Pelletier

---

## Quick Facts About You

- **Current Status:** Contract wrapping up, available in 1-2 weeks depending on NASA's needs
- **Progression:** Associate → Lead Developer over 5 years
- **PatientNow:** 4 years, promoted to Lead, managed team of 8
- **Current Project:** Mia Aesthetics enterprise WordPress theme

---

## Quick Glance Cheat Sheet (Keep This Visible During Call)

**The Four Skills:**

- **Plugins:** Featured image column, transient cleaner, GF file cleanup, Elementor widgets
- **Backend:** 8 CPTs, conditional asset loading, caching with auto-clear
- **Database:** WP_Query, transients, `save_post` to clear cache, batch queries (7→2 calls), avoid JOINs
- **REST API:** Zip code → 3 closest locations with miles, Google Maps, Haversine formula

**Key Hooks (Memorize These):**

- `pre_get_posts` - modify queries before they run
- `save_post` - clear cache when content changes
- `wp_enqueue_scripts` - load CSS/JS conditionally
- `body_class` - add template CSS classes

**Quick Definitions:**

- Hook = spot to plug in your code
- Action = run code when X happens
- Filter = modify data before it's used
- Enqueue = load files the WordPress way

**Security (Quick):** `esc_html`, `esc_url`, `sanitize_text_field`, `current_user_can`

**Closing Line:**

> "I know I have the experience and progression to be a valuable member of this team."

**Availability:** "My contract is wrapping up. Available in 1-2 weeks."

---

## Mindset Reminders

1. **You know this stuff.** The terminology tripped you up, not the knowledge.
2. **Own mistakes directly.** No excuses. "I stumbled, here's what I actually know."
3. **Be specific.** "I use `pre_get_posts`" is better than "I modify queries."
4. **Use their language.** Say "hooks" not "functions that run when stuff happens."
5. **Close strong.** "I know" not "I feel." Confidence, not hope.

---

## Pre-Interview Checklist

- [ ] Review this document
- [ ] Have WORDPRESS-TECHNIQUES.md open for reference
- [ ] Have SKILLS-PORTFOLIO.md open for terminology mapping
- [ ] Take a breath - you've built real things
- [ ] Remember: they called YOU back

---

## The Four Skills They Mentioned

### 1. Custom Plugin Development

#### Your Experience

| Plugin                | Purpose               | Technical Details                                                                       |
| --------------------- | --------------------- | --------------------------------------------------------------------------------------- |
| Featured Image Column | Admin UX improvement  | Hooks into `manage_{post_type}_posts_columns`, `manage_{post_type}_posts_custom_column` |
| Transient Cleaner     | Database optimization | Removes expired transients, clears orphaned data                                        |
| Gravity Forms Cleanup | File management       | Removes uploaded files from `wp-content` after processing                               |
| Elementor Extensions  | Custom functionality  | Extended Elementor widgets and controls                                                 |

#### If Asked "Have you built custom plugins?"

> "Yes. I've built utility plugins for admin improvements like featured image columns in post lists, database optimization plugins that clean up transients, and file management plugins that handle Gravity Forms uploads. I've also extended Elementor with custom functionality. These aren't flashy plugins, but they solve real operational problems - keeping the database clean, improving editor workflow, and managing file storage."

**If they ask for a specific example:** "The featured image column plugin. Editors were scrolling through hundreds of posts trying to find the right one - they couldn't see images without clicking into each post. I hooked into `manage_posts_columns` to add a column, then `manage_posts_custom_column` to output a 50x50 thumbnail for each row. Now they can scan visually and find what they need in seconds."

**If they ask about plugin structure:**

- Header comment declares plugin name, version, author
- Hook into `plugins_loaded` or `init` for setup
- Namespace functions or use a class to avoid conflicts
- Register activation/deactivation hooks if needed for setup/cleanup

**Your Plugins:**

| Plugin | Problem It Solves | How It Works |
|--------|-------------------|--------------|
| Featured Image Column | Can't see images in post list | Hooks into admin columns, outputs thumbnail |
| Transient Cleaner | Expired cache piles up in database | Queries `_transient_timeout_*` options, deletes expired |
| Gravity Forms Cleanup | Uploaded files fill up server | Scheduled task removes files after X days |
| Elementor Widgets | Built-in widgets don't fit client needs | Extends Elementor's widget base class |

**Plugin Lifecycle Hooks:**

| Hook | When It Runs | What You Do |
|------|--------------|-------------|
| `register_activation_hook()` | Plugin activated | Set default options, create DB tables, flush rewrites |
| `register_deactivation_hook()` | Plugin deactivated | Clean temp data, but keep settings |
| `register_uninstall_hook()` | Plugin deleted | Remove everything - options, tables, files |

**Common Plugin APIs:**

| API | Purpose | Functions |
|-----|---------|-----------|
| **Options** | Store settings | `get_option()`, `update_option()`, `delete_option()` |
| **Admin Menus** | Settings pages | `add_menu_page()`, `add_submenu_page()`, `add_options_page()` |
| **Shortcodes** | Embed in content | `add_shortcode( 'tag', 'callback' )` |
| **AJAX** | Async requests | `wp_ajax_{action}`, `wp_ajax_nopriv_{action}` |
| **Cron** | Scheduled tasks | `wp_schedule_event()`, `wp_schedule_single_event()` |
| **Transients** | Cached data | `get_transient()`, `set_transient()`, `delete_transient()` |

---

### 2. Backend Development

#### Your Experience (from Mia Aesthetics)

- **8 Custom Post Types** with parent/child relationships (surgeon, procedure, location, case, special, condition, non-surgical, fat-transfer)
- **Conditional Asset Loading** - Only load CSS/JS when that component is actually on the page
- **Query Optimization** - Skip unnecessary database work (pagination counts, extra fields)
- **Caching** - Save expensive data so we don't re-fetch it on every page load
- **Auto-clear Cache** - When someone updates a post, delete the old saved data automatically
- **ACF Pro Integration** - Always validate field data before using it

#### If Asked "Describe your backend development experience"

> "At Mia Aesthetics, I built a theme with 8 custom post types that have parent/child relationships - locations have child offices, procedures have sub-procedures. I built a system that only loads CSS and JavaScript when that component is actually needed on the page. For database performance, I skip unnecessary work - like pagination counts when I don't need them. I also cache expensive data and automatically clear it when content changes, so users always see fresh data without the site being slow."

**If they ask how the conditional loading works:** "Each template has a map that says 'this template needs these CSS and JS files.' When the page loads, I check what template we're on and only load what's in that map - instead of loading everything everywhere."

**If they ask how the cache clearing works:** "I hook into `save_post` - that's an action that fires whenever content is saved. When it fires, I delete the cached data for that post type, so the next page load rebuilds it fresh."

---

### 3. Database Integration

#### Your Database Experience

| What I Do             | Plain English                                                          |
| --------------------- | ---------------------------------------------------------------------- |
| WP_Query Optimization | Only ask the database for what I actually need                         |
| Meta Queries          | Filter posts by custom field values (like "show only active specials") |
| Caching               | Save results so I don't hit the database on every page load            |
| Transients            | Temporary saved data that expires after a set time                     |
| Auto-clear on Save    | When content updates, delete old cached data automatically             |
| Batch Queries         | Get multiple things in one call instead of looping                     |
| Avoid JOINs           | Look up by ID instead of filtering - faster on big tables              |

#### Concrete Examples You Built

| Problem | Solution | Result |
|---------|----------|--------|
| Footer needs surgeons for 6 locations | One query with "give me surgeons at ANY of these locations" instead of 6 separate queries | 7 database calls → 2 |
| Gallery category filter is slow | Get post IDs directly from taxonomy table, then look up by ID (indexed) instead of JOIN | Instant filtering on 1000+ cases |
| Navigation data hit on every page | Cache the menu data, auto-clear when locations/surgeons change | Database only hit when content changes |
| Specials need date filtering | Meta query with "end date >= today OR no end date" | Only active specials show |

#### If Asked "How do you work with the WordPress database?"

> "I use WP_Query and optimize it based on what I actually need - if I just need post IDs, I tell it to skip fetching full objects. For data that's expensive to generate, I cache it in transients and clear it automatically when content changes. That keeps the site fast while making sure users always see fresh data."

**If they ask for specifics, you can add:** "For example, I use `fields => 'ids'` and `no_found_rows => true` to skip work I don't need, and hook into `save_post` to clear caches."

**If they ask for a concrete example:** "Our navigation menus show all locations and surgeons dynamically. When an editor adds a new location or removes a surgeon, it updates across the entire site - header, footer, location finder, everywhere. I cache that data so we're not hitting the database on every page load, but the cache clears automatically when someone saves a location or surgeon post."

**If they ask about batch queries:** "Our footer shows surgeons grouped by 6 locations. Instead of asking the database 'which surgeons work here?' for each location - that's 7 calls minimum - I ask once: 'give me all surgeons who work at ANY of these locations.' Then I sort them into groups with PHP. Seven database calls become two."

**If they ask about avoiding JOINs:** "For our before/after gallery, filtering by category normally uses a JOIN - the database has to match rows across tables. Instead, I ask the taxonomy table directly for the post IDs, then look up those posts by their ID. ID lookups are instant because they're indexed. The JOIN has to scan and match."

**If they ask about caching strategy:** "I use two types. Transients live in the database - good for data that should survive server restarts, like navigation menus. Object cache lives in memory - faster but temporary. I pick based on how often it changes and how expensive it is to rebuild."

---

### 4. REST API

#### Your REST API Experience

```javascript
// Fetch all locations via REST API, get coordinates from ACF fields
const response = await fetch('/wp-json/wp/v2/location?per_page=100&parent=0&_fields=id,title,link,acf');

// User enters zip → Google geocodes it → I calculate distance to each location
location.distance = calculateDistance(userLat, userLng, locationLat, locationLng);

// Sort by distance, show top 3 closest with "X miles away"
results.sort((a, b) => a.distance - b.distance).slice(0, 3);
```

**What You Built:**

- **Location finder:** User enters zip code → shows the 3 closest locations with distance in miles
- **Distance calculation:** Used Haversine formula to calculate miles between user and each location
- **Google Maps integration:** ACF has a Google Maps field - editors type an address, it stores coordinates, REST API delivers them, Google Maps displays pins
- **Careers location search:** Same concept, different context - find nearest office with job openings
- **Virtual consultation:** Fetch location coordinates via chained API calls to pre-fill forms
- **Performance:** Used `_fields` parameter to only fetch the data I actually need

#### If Asked "Have you worked with WordPress REST API?"

> "Yes. I built a location finder where users enter their zip code and it shows the 3 closest offices with how many miles away each one is. I fetch the location data via the REST API, calculate the distances, sort them, and display the results on a Google Map. I also built a careers version that does the same thing for job seekers."

**If they ask for specifics, you can add:** "ACF has a Google Maps field type - editors just type the address or business name and it stores the coordinates. I fetch those via the REST API, calculate distance with the Haversine formula, sort by closest, and plot them on a map."

#### What You Should Know (But Didn't Build Yet)

| Concept                 | Plain English                                                 | Could You Do It?                   |
| ----------------------- | ------------------------------------------------------------- | ---------------------------------- |
| `register_rest_route()` | Make your own API URL that returns custom data                | Yes - know the pattern             |
| `WP_REST_Controller`    | A PHP class template for building API endpoints               | Familiar - haven't needed it yet   |
| `permission_callback`   | Check "is this person allowed to access this?" before running | Yes - same as `current_user_can()` |
| Nonces in REST          | A security token that proves the request came from your site  | Yes - used nonces in forms         |
| Headless WordPress      | WordPress handles data, a separate app handles the display    | Know how it works                  |
| CRUD via REST           | Not just reading data - also creating, updating, deleting     | Know it's possible                 |

#### If Asked "Have you created custom REST endpoints?"

> "I've consumed the REST API heavily - fetching location data, ACF fields, and chaining calls together. I haven't needed to create custom endpoints from scratch, but I know the pattern: you register a route, define what data it returns, and add a security check to control who can access it. It's the same permission concepts I use elsewhere."

---

## Hooks - The Question You Stumbled On

**What Hooks Actually Are:**

Hooks = WordPress's way of saying "run my code when X happens" or "let me modify Y before it's used"

- **Action Hook** = "Do something when this event happens" (like: when a post saves, clear my cache)
- **Filter Hook** = "Change this data before WordPress uses it" (like: add my CSS class to the body tag)

**The Simple Answer for the Interview:**

> "Hooks are how I plug my code into WordPress without editing core files. Actions let me run code when something happens - like clearing cached data when a post saves. Filters let me modify data - like adding CSS classes to the body tag based on which template is loaded."

**Hooks You Use Daily:**

| Hook Name                   | Type   | What It Does (Plain English)                                          |
| --------------------------- | ------ | --------------------------------------------------------------------- |
| `pre_get_posts`             | Action | Change how WordPress fetches posts before it runs the query           |
| `save_post`                 | Action | Do something when a post is saved (I use it to clear old cached data) |
| `wp_enqueue_scripts`        | Action | Load CSS/JS files - I check what's needed first                       |
| `wp_head`                   | Action | Add stuff to the `<head>` tag (tracking scripts, etc.)                |
| `body_class`                | Filter | Add CSS classes to the body tag                                       |
| `wpseo_schema_graph_pieces` | Filter | Add my own structured data to Yoast's SEO output                      |
| `upload_mimes`              | Filter | Allow SVG uploads (but only for admins)                               |

**"Must-Know" Hooks They Might Ask About:**

| Hook                 | Type   | What It Does                                         | Have You Used It?                            |
| -------------------- | ------ | ---------------------------------------------------- | -------------------------------------------- |
| `init`               | Action | Runs after WordPress loads, before anything displays | Yes - registering CPTs, custom functionality |
| `wp_head`            | Action | Add things to `<head>`                               | Yes - tracking scripts                       |
| `wp_footer`          | Action | Add things before `</body>`                          | Yes - scripts that load last                 |
| `wp_enqueue_scripts` | Action | Load CSS/JS the right way                            | Yes - conditional loading                    |
| `admin_init`         | Action | Admin area setup                                     | Yes - disable comments                       |
| `admin_menu`         | Action | Add/remove admin menu items                          | Yes - hide comments menu                     |
| `save_post`          | Action | Do something when post saves                         | Yes - clear cached data                      |
| `pre_get_posts`      | Action | Modify queries before they run                       | Yes - archive ordering                       |
| `after_setup_theme`  | Action | Theme setup (menus, image sizes)                     | Yes - theme features                         |
| `the_content`        | Filter | Change post content before display                   | Know it, use sparingly                       |
| `the_title`          | Filter | Change post title before display                     | Know it                                      |
| `body_class`         | Filter | Add CSS classes to body tag                          | Yes - template classes                       |
| `excerpt_length`     | Filter | Change how long excerpts are                         | Yes - context-aware length                   |
| `upload_mimes`       | Filter | Allow/block file types                               | Yes - SVG for admins                         |
| `wp_nav_menu_items`  | Filter | Modify menu items                                    | Know it                                      |

**If Asked About Hooks Again:**

> "I use hooks all the time. For actions - `pre_get_posts` to change how archives fetch posts, `save_post` to clear cached data when content changes, `wp_enqueue_scripts` to load CSS and JS only when needed. For filters - `body_class` to add template-specific CSS classes, and I extended Yoast SEO's structured data output to add custom doctor and clinic information. I also create my own hooks so other developers can modify my theme's output."

---

## Elementor (If It Comes Up)

### Your Elementor Experience

- **Theme Builder** - Built complete site templates (headers, footers, single templates, archive templates)
- **Display Conditions** - Know how to set which templates show where
- **Dynamic Data** - Connected templates to ACF fields and post data
- **Custom Widgets** - Built custom Elementor widgets and extensions
- **Widget API** - Understand how to register widgets and add controls

### If Asked "Have you worked with Elementor?"

> "Yes, I'm comfortable with Elementor, especially the Theme Builder. I've built complete site structures - custom headers, footers, single post templates, archive templates - and set up the display conditions to control where each shows. I've connected templates to dynamic data from ACF fields. I've also built custom widgets when the built-in ones didn't do what I needed. That said, my current project at Mia Aesthetics is a custom-coded theme - we wanted full control over the markup for performance and accessibility. But I can work in either environment."

**Theme Builder Concepts (In Case They Dig Deeper):**

| Concept            | What It Means                                                           |
| ------------------ | ----------------------------------------------------------------------- |
| Theme Builder      | Create custom templates for any part of the site                        |
| Display Conditions | Rules for where a template appears (all posts, specific category, etc.) |
| Dynamic Tags       | Pull in data like post title, featured image, ACF fields                |
| Template Parts     | Header, footer, single, archive, 404, search results                    |
| Global Widgets     | Reusable widgets that update everywhere when you edit once              |

---

## Gutenberg / Block Editor

### What "Gutenberg Block Methodology" Means

Gutenberg blocks are **React components** that follow a specific registration pattern:

| Component | Purpose |
|-----------|---------|
| **block.json** | Metadata file - declares name, category, attributes, supports |
| **Edit function** | What editors see in the admin (React component) |
| **Save function** | What gets output on the frontend (or null for dynamic blocks) |
| **Attributes** | Data the block stores (like text content, settings, IDs) |
| **Supports** | Built-in features like colors, spacing, alignment |

### If Asked "What's your experience with Gutenberg blocks?"

> "My current project uses ACF and custom templates rather than custom blocks - that was the right fit for the content editors. But I understand how Gutenberg blocks work. They're React components that register with WordPress - you define what the editor sees, what gets saved, and what attributes the block stores. The block.json file declares the block's metadata and settings."

**If they ask about the methodology:** "Gutenberg blocks follow a specific pattern: you have an Edit component for the admin interface, a Save component for the frontend output, and attributes that store the block's data. The block.json file registers everything - name, category, what features it supports like colors or spacing. It's React under the hood, but WordPress provides helper components so you're not starting from scratch."

**If they ask if you could build one:** "Yes. The pattern is clear - register the block, define the edit and save functions, declare attributes for any configurable options. I've worked with React and understand component-based architecture. The WordPress block API just wraps that in their registration system."

### Block Types You Should Know

| Type | Description |
|------|-------------|
| **Static block** | Save function outputs HTML directly |
| **Dynamic block** | PHP renders the output (Save returns null), good for data that changes |
| **Server-side rendering** | Block registered in PHP with `register_block_type()` and a render callback |

---

## Schema.org / Structured Data (Impressive Work - Don't Undersell)

### What You Built

You extended Yoast SEO's schema output with custom OOP classes:

```php
// You wrote custom PHP classes that plug into Yoast's schema graph
class Surgeon_Schema {
    public function is_needed(): bool {
        return is_singular( 'surgeon' );
    }

    public function generate(): array {
        return array(
            '@type' => array( 'Person', 'Physician' ),
            'medicalSpecialty' => 'PlasticSurgery',
            // ... doctor-specific structured data
        );
    }
}
```

### Why This Matters

- Most WordPress devs never touch structured data
- You wrote OOP PHP classes (not just procedural code)
- You integrated with a third-party plugin at a deep level
- Google uses this data for rich search results

### If Asked About SEO or Structured Data

> "I added custom structured data for our surgeons and locations. When Google crawls a surgeon page, it sees proper 'Physician' markup with their specialty and credentials. For location pages, it sees 'MedicalClinic' markup with address and contact info. I built PHP classes that plug into Yoast SEO and output the right data for each page type. It helps Google understand our content and show rich results."

**If they ask how:** "Yoast has a filter I hook into - I add my own classes that check what page we're on and output the appropriate schema."

---

## Security (Important for NASA/Government)

### What You Practice

| Security Measure         | How You Use It                                                             |
| ------------------------ | -------------------------------------------------------------------------- |
| Output Escaping          | `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()` based on context |
| Input Sanitization       | `sanitize_text_field()`, `sanitize_email()` before saving                  |
| Capability Checks        | `current_user_can('manage_options')` before sensitive operations           |
| Nonces                   | `wp_nonce_field()` / `wp_verify_nonce()` for form security                 |
| Direct Access Prevention | `if (!defined('ABSPATH')) exit;` in all PHP files                          |

### If Asked About Security

> "I follow WordPress security best practices. Output escaping based on context - `esc_html` for text, `esc_url` for URLs, `esc_attr` for attributes. Input sanitization before saving anything to the database. Capability checks before sensitive operations - like only allowing SVG uploads for administrators. Nonces for form submissions. And preventing direct file access with the ABSPATH check."

---

## Template Hierarchy (You Know This)

### How It Works

WordPress automatically loads specific template files based on what's being viewed:

```bash
front-page.php      → Homepage
single-surgeon.php  → Individual surgeon page
archive-surgeon.php → Surgeon listing page
page-careers.php    → Specific page by slug
```

### Your Template Experience

- 30+ template files following WordPress naming conventions
- 8 single templates for each custom post type
- 8 archive templates for each custom post type
- Page templates for specific pages

### If Asked About Template Hierarchy

> "I follow WordPress template hierarchy. In Mia Aesthetics I built over 30 templates - single templates for each of the 8 custom post types, archive templates for listings, and page templates for specific pages like careers. WordPress automatically picks the right template based on what's being viewed."

---

## Accessibility

### What You Implement (WCAG 2.1 AA)

| Technique         | Example                                                         |
| ----------------- | --------------------------------------------------------------- |
| ARIA Labels       | `aria-label="Primary navigation"` on nav elements               |
| ARIA Expanded     | `aria-expanded="false"` on mobile menu toggles                  |
| ARIA Controls     | `aria-controls="siteMenu"` linking buttons to what they control |
| Landmark Roles    | `<nav>`, `<main>`, `<aside>` for screen readers                 |
| Alt Text          | Meaningful descriptions for informational images                |
| Decorative Images | `alt=""` or `aria-hidden="true"` for decorative elements        |

### If Asked About Accessibility

> "I follow WCAG 2.1 AA guidelines. That means using proper HTML elements - a `<nav>` for navigation, `<main>` for content, `<button>` for buttons instead of styled divs. For interactive elements like menus, I add ARIA attributes so screen readers know what's happening - like telling it 'this menu is open' or 'this button controls that panel.' Images get meaningful alt text if they convey information, or empty alt if they're decorative."

**Note:** Section 508 (federal requirement) references WCAG 2.0 AA. Your WCAG 2.1 AA experience meets and exceeds that standard, but don't claim Section 508 experience directly unless asked - just say you follow WCAG 2.1 AA.

---

## PHPStan Level 8 (Strict Type Safety)

**What It Is (Simple):**

PHPStan is a tool that reads your PHP code and finds bugs _before_ you run it. Level 8 is the strictest setting - it catches more potential problems.

**What It Means In Practice:**

- I can't be lazy with data types - if something might be null, I have to check for it
- Every function says what type of data it returns
- The tool catches bugs that would normally only show up when a user hits a broken page
- It's like having a really strict code reviewer that never sleeps

**Example Pattern:**

```php
// Level 8 requires explicit type checking
$field = get_field( 'name' );

// Can't do: if ( $field ) - because $field could be null, false, empty string, 0
// Must do:
if ( is_string( $field ) && '' !== $field ) {
    echo esc_html( $field );
}
```

---

## Potential "Zinger" Questions

### "What's the difference between an action and a filter?"

> "Actions do something - they execute code at a specific point. Filters modify something - they take data, change it, and return it. `save_post` is an action that fires when a post saves. `body_class` is a filter that takes the array of body classes and returns a modified array."

### "How do you optimize a slow WordPress site?"

> "I start with queries - are we running expensive queries on every page load? I look for missing indexes, unnecessary meta queries, and queries that could be cached. Then asset loading - are we loading scripts globally that should be conditional? Then caching - object cache for expensive operations, transients for data that doesn't change often, full page caching at the server level. Then images - proper sizing, lazy loading, WebP format."

**If they ask how you identify slow queries:** "I use Query Monitor - it's a plugin that shows every database query on the page, how long each took, and what triggered it. I look for queries that are slow or that run on every page when they shouldn't."

### "What's your experience with PHP static analysis?"

> "I use PHPStan at the strictest level on my current project. It reads my code and finds bugs before I even run it - like if a function might return null but I forgot to handle that case. It's like having a really strict code reviewer that catches problems before they reach users."

**If they ask what "Level 8" means:** "PHPStan has levels 0-9, where higher means stricter. Level 8 requires explicit type checking everywhere - you can't be lazy about handling edge cases."

### "How do you handle security in WordPress?"

> "Three things: clean what comes in, escape what goes out, and check who's doing it. Before I save any user input, I sanitize it. Before I display anything, I escape it based on context - different functions for text, URLs, and HTML. And before sensitive operations, I check if the user has permission - like only letting administrators upload SVG files."

**If they want specifics:** "For escaping: `esc_html()` for text, `esc_url()` for links. For sanitizing: `sanitize_text_field()`. For permissions: `current_user_can()`."

### "What's your process when you don't know something?"

> "I research it. WordPress has excellent documentation, and I use the developer references frequently. If I'm stuck on something, I'll read the core source code to understand how a function actually works. I also maintain documentation for my own projects so I don't have to solve the same problem twice."

### "How do you debug a plugin that's not working?"

> "First I check if it's actually the plugin - I deactivate it and see if the problem goes away. If it is the plugin, I check the error log and turn on `WP_DEBUG` to see what's happening. I look for conflicts by deactivating other plugins one by one. If I need to dig deeper, I'll add some logging to trace the code path, or use Query Monitor to see what hooks are firing and what queries are running. Most issues come down to conflicts, missing dependencies, or PHP errors that are being hidden."

**If they ask for specifics:**

- **Error logs:** `wp-content/debug.log` with `WP_DEBUG_LOG` enabled
- **Query Monitor plugin:** Shows hooks, queries, PHP errors, HTTP requests
- **Conflict testing:** Switch to default theme, deactivate all plugins, reactivate one by one
- **Hook debugging:** `did_action('hook_name')` to check if a hook fired
- **Database issues:** Check `wp_options` for corrupted plugin settings

### "Tell me about a mistake you made and how you handled it."

> "In my recent interview with your team, I blanked on the term 'hooks' even though I use them daily. I call them functions in my head, not hooks. I could have made excuses, but instead I followed up to demonstrate that I do understand the concepts - I just had a terminology disconnect under pressure. That's why I'm here - I want to show you what I actually know."

---

## Strong Closing Statements

**Use "I know" not "I feel":**

> "Thank you for speaking with me again. Based on our conversation, I know I have the battle-tested experience and the progression to be a valuable member of this team."
>
> "Thank you for the opportunity. I know I have the technical depth and the track record to contribute from day one."
>
> "I appreciate you taking the time. I know my experience - from Associate to Lead over five years, building enterprise-level WordPress solutions - makes me the right fit for this role."

**If They Try to End Quickly:**

> "Before we wrap up - I want you to know I'm confident I can deliver for this team. I have the experience, the progression, and I'm ready to get to work."

---

## Your Availability Statement

> "My current contract is wrapping up. I can be available within 1-2 weeks depending on NASA's timeline and onboarding requirements."

---

## Summary: Your Technical Arsenal

| Category              | What You've Done                                                               |
| --------------------- | ------------------------------------------------------------------------------ |
| **Hooks**             | 15+ actions, 25+ filters, plus custom hooks you created                        |
| **Custom Post Types** | 8 content types with parent/child relationships                                |
| **REST API**          | Location search fetching data via API, displaying on Google Maps               |
| **Database**          | Optimized queries, caching, auto-clear when content updates                    |
| **Plugins**           | Featured image column, database cleanup, file management, Elementor extensions |
| **Security**          | Clean user input, make output safe, check user permissions                     |
| **Type Safety**       | Strictest PHP analysis (PHPStan Level 8) - catches bugs before they go live    |
| **Performance**       | Only load what's needed, fast hero images, responsive image sizes              |
| **Standards**         | WordPress coding standards, accessibility (WCAG 2.1 AA)                        |

---

## Jargon Glossary (Quick Reference)

| Term They Might Say        | What It Actually Means                                               |
| -------------------------- | -------------------------------------------------------------------- |
| **Hook**                   | A spot where you can plug in your own code                           |
| **Action Hook**            | Run my code when X happens                                           |
| **Filter Hook**            | Let me change this data before it's used                             |
| **Enqueue**                | Load a CSS or JS file the WordPress way                              |
| **Cache**                  | Saved copy of data so you don't re-fetch it                          |
| **Cache Invalidation**     | Delete the saved copy because the original changed                   |
| **Transient**              | Temporary saved data that expires after a set time                   |
| **Object Cache**           | Faster temporary storage (in memory, not database)                   |
| **WP_Query**               | WordPress's way to fetch posts from the database                     |
| **Custom Post Type (CPT)** | A new content type beyond posts/pages (like "Surgeon" or "Location") |
| **Meta Query**             | Filter posts by custom field values                                  |
| **REST API**               | Fetch WordPress data via URLs (like `/wp-json/wp/v2/posts`)          |
| **Sanitize**               | Clean user input before saving it                                    |
| **Escape**                 | Make data safe before displaying it                                  |
| **PHPStan**                | Tool that finds PHP bugs before you run the code                     |
| **PHPCS**                  | Tool that checks if your code follows WordPress style rules          |
| **Conditional Loading**    | Only load stuff when it's actually needed                            |
| **LCP**                    | Largest Contentful Paint - how fast the main content appears         |
| **srcset**                 | Multiple image sizes so browsers pick the best one                   |
| **WCAG**                   | Accessibility guidelines (AA = the level we follow)                  |
| **Schema.org**             | Structured data that helps Google understand your content            |
| **ACF**                    | Advanced Custom Fields - plugin for adding custom data to posts      |
