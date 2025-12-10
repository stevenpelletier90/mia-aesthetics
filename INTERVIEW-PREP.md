# Interview Preparation Document

NASA Web Developer Position - Steve Pelletier

---

## Quick Facts About You

- **Current Status:** Contract wrapping up, available in 1-2 weeks depending on NASA's needs
- **Progression:** Associate → Lead Developer over 5 years
- **PatientNow:** 4 years, promoted to Lead, managed team of 8
- **Current Project:** Mia Aesthetics enterprise WordPress theme

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

#### If Asked "How do you work with the WordPress database?"

> "I use WordPress's built-in tools - WP_Query for fetching posts, and caching to avoid hitting the database on every page load. I optimize queries by only asking for what I need - if I just need a count, I don't fetch full post objects. For data that's expensive to generate, I save it temporarily and set it to auto-clear when someone updates related content. That way the site stays fast but data is always fresh."

---

### 4. REST API

#### Your REST API Experience

```javascript
// Location search - fetches CPT data with ACF fields
const response = await fetch('/wp-json/wp/v2/location?per_page=100&parent=0&_fields=id,title,link,acf');

// Chained API calls for related data
const acfResponse = await fetch(`/wp-json/wp/v2/pages/${page.id}?_fields=id,title,link,acf`);
const locationResponse = await fetch(`/wp-json/wp/v2/location/${mainLocationId}?_fields=acf`);
```

**What You Built:**

- Location search that queries the REST API and displays results on a Google Map
- Virtual consultation page that fetches location coordinates via API
- Careers location finder with chained API calls to get related ACF data
- Used `_fields` parameter to limit response payload for performance

#### If Asked "Have you worked with WordPress REST API?"

> "Yes. In Mia Aesthetics I built location search features that query the REST API to fetch custom post type data including ACF fields, then display results on Google Maps. I chain multiple API calls when I need related data - for example, fetching a page, then fetching its linked location to get coordinates. I use the `_fields` parameter to limit response payload and improve performance."

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

## Potential "Zinger" Questions

### "What's the difference between an action and a filter?"

> "Actions do something - they execute code at a specific point. Filters modify something - they take data, change it, and return it. `save_post` is an action that fires when a post saves. `body_class` is a filter that takes the array of body classes and returns a modified array."

### "How do you optimize a slow WordPress site?"

> "I start with queries - are we running expensive queries on every page load? I look for missing indexes, unnecessary meta queries, and queries that could be cached. Then asset loading - are we loading scripts globally that should be conditional? Then caching - object cache for expensive operations, transients for data that doesn't change often, full page caching at the server level. Then images - proper sizing, lazy loading, WebP format."

### "What's your experience with PHP static analysis?"

> "I enforce PHPStan Level 8 with bleeding edge rules on my current project. That means strict return types, no `empty()` calls, explicit type checking on all mixed types. It catches bugs before they hit production - things like null returns that would cause fatal errors."

### "How do you handle security in WordPress?"

> "Output escaping based on context - `esc_html()` for text, `esc_url()` for URLs, `esc_attr()` for attributes, `wp_kses_post()` for trusted HTML. Input sanitization with `sanitize_text_field()` and validation with `filter_var()`. Capability checks before sensitive operations - like only allowing SVG uploads for administrators."

### "What's your process when you don't know something?"

> "I research it. WordPress has excellent documentation, and I use the developer references frequently. If I'm stuck on something, I'll read the core source code to understand how a function actually works. I also maintain documentation for my own projects so I don't have to solve the same problem twice."

### "Tell me about a mistake you made and how you handled it."

> "In my recent interview with your team, I blanked on the term 'hooks' even though I use them daily. I call them functions in my head, not hooks. I could have made excuses, but instead I followed up to demonstrate that I do understand the concepts - I just had a terminology disconnect under pressure. That's why I'm here - I want to show you what I actually know."

---

## PHPStan Level 8 - In Case They Ask

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

## Your Availability Statement

> "My current contract is wrapping up. I can be available within 1-2 weeks depending on NASA's timeline and onboarding requirements."

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
