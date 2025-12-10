# Interview Q&A Practice Sheet

Quick practice format - read the question, answer out loud, check your response.

---

## The Four Skills

**Q: Have you built custom plugins?**

> Yes. I've built utility plugins - a featured image column that shows thumbnails in the admin post list, a transient cleaner that removes expired cached data, a Gravity Forms cleanup plugin that handles uploaded files, and custom Elementor widgets. They're not flashy, but they solve real problems for editors and site performance.

---

**Q: Describe your backend development experience.**

> I built a theme with 8 custom post types that have parent/child relationships - locations have child offices, procedures have sub-procedures. I built a system that only loads CSS and JavaScript when that component is actually needed on the page. For performance, I cache expensive data and automatically clear it when content changes, so users always see fresh data without the site being slow.

_If they ask how the conditional loading works:_ "Each template has a map that says 'this template needs these CSS and JS files.' When the page loads, I check what template we're on and only load what's in that map - instead of loading everything everywhere."

_If they ask how the cache clearing works:_ "I hook into `save_post` - that's an action that fires whenever content is saved. When it fires, I delete the cached data for that post type, so the next page load rebuilds it fresh."

---

**Q: How do you work with the WordPress database?**

> I use WP_Query and optimize it based on what I actually need - if I just need post IDs, I tell it to skip fetching full objects. For data that's expensive to generate, I cache it in transients and clear it automatically when content changes. That keeps the site fast while making sure users always see fresh data.

_If they want specifics:_ "I use `fields => 'ids'` and `no_found_rows => true` to skip work I don't need, and hook into `save_post` to clear caches."

---

**Q: Have you worked with WordPress REST API?**

> Yes. I built a location finder where users enter their zip code and it shows the 3 closest offices with how many miles away each one is. I fetch the location data via the REST API, calculate the distances, sort them, and display the results on a Google Map. I also built a careers version that does the same thing for job seekers.

_If they want specifics:_ "ACF has a Google Maps field type - editors just type the address or business name and it stores the coordinates. I fetch those via the REST API, calculate distance with the Haversine formula, sort by closest, and plot them on a map."

---

**Q: Have you created custom REST endpoints?**

> I've consumed the REST API heavily - fetching location data, ACF fields, and chaining calls together. I haven't needed to create custom endpoints from scratch, but I know the pattern: you register a route, define what data it returns, and add a security check to control who can access it. It's the same permission concepts I use elsewhere.

---

## Hooks

**Q: What are hooks in WordPress?**

> Hooks are how I plug my code into WordPress without editing core files. Actions let me run code when something happens - like clearing cached data when a post saves. Filters let me modify data - like adding CSS classes to the body tag based on which template is loaded.

---

**Q: What's the difference between an action and a filter?**

> Actions do something - they execute code at a specific point. Filters modify something - they take data, change it, and return it. `save_post` is an action that fires when a post saves. `body_class` is a filter that takes the array of body classes and returns a modified array.

---

**Q: What hooks do you use regularly?**

> For actions - `pre_get_posts` to change how archives fetch posts, `save_post` to clear cached data when content changes, `wp_enqueue_scripts` to load CSS and JS only when needed. For filters - `body_class` to add template-specific CSS classes, and I hooked into Yoast SEO to add custom structured data for doctors and clinics.

---

## Elementor

**Q: Have you worked with Elementor?**

> Yes, I'm comfortable with Elementor, especially the Theme Builder. I've built complete site structures - custom headers, footers, single post templates, archive templates - and set up the display conditions to control where each shows. I've connected templates to dynamic data from ACF fields. I've also built custom widgets when the built-in ones didn't do what I needed. That said, my current project is a custom-coded theme - we wanted full control over the markup for performance and accessibility. But I can work in either environment.

---

## Schema / SEO

**Q: Tell me about your SEO or structured data experience.**

> I added custom structured data for our surgeons and locations. When Google crawls a surgeon page, it sees proper "Physician" markup with their specialty, credentials, and practice location. For location pages, it sees "MedicalClinic" markup with address and contact info. I built PHP classes that plug into Yoast SEO and output the right data for each page type. It helps Google understand our content and show rich results in search.

_If they ask how:_ "Yoast has a filter called `wpseo_schema_graph_pieces` - I add my own classes that check what page we're on and output the appropriate schema."

---

## Security

**Q: How do you handle security in WordPress?**

> Three things: clean what comes in, escape what goes out, and check who's doing it. Before I save any user input, I sanitize it. Before I display anything, I escape it based on context - different functions for text, URLs, and HTML. And before sensitive operations, I check if the user has permission - like only letting administrators upload SVG files.

_If they want specifics:_ "For escaping: `esc_html()` for text, `esc_url()` for links, `esc_attr()` for attributes. For sanitizing: `sanitize_text_field()`. For permissions: `current_user_can()`."

---

## Accessibility

**Q: What's your experience with accessibility?**

> I follow WCAG 2.1 AA guidelines. That means using proper HTML elements - a `<nav>` for navigation, `<main>` for content, `<button>` for buttons instead of styled divs. For interactive elements like menus, I add ARIA attributes so screen readers know what's happening - like telling it "this menu is open" or "this button controls that panel." Images get meaningful alt text if they convey information, or empty alt if they're decorative.

---

## Performance

**Q: How do you optimize a slow WordPress site?**

> I start with queries - are we running expensive queries on every page load? I look for unnecessary queries that could be cached. Then asset loading - are we loading scripts globally that should be conditional? Then caching - transients for data that doesn't change often, full page caching at the server level. Then images - proper sizing, lazy loading, WebP format.

_If they ask how I identify slow queries:_ "I use Query Monitor - it's a plugin that shows every database query on the page, how long each took, and what triggered it. I look for queries that are slow or that run on every page when they shouldn't."

---

## PHP / Code Quality

**Q: What's your experience with PHP static analysis?**

> I use PHPStan at the strictest level on my current project. It reads my code and finds bugs before I even run it - like if a function might return null but I forgot to handle that case. It's like having a really strict code reviewer that catches problems before they reach users.

_If they ask what "Level 8" means:_ "PHPStan has levels 0-9, where higher means stricter. Level 8 requires explicit type checking everywhere - you can't be lazy about handling edge cases."

---

## Debugging

**Q: How do you debug a plugin that's not working?**

> First I check if it's actually the plugin - I deactivate it and see if the problem goes away. If it is the plugin, I check the error log and turn on `WP_DEBUG` to see what's happening. I look for conflicts by deactivating other plugins one by one. If I need to dig deeper, I'll add some logging to trace the code path, or use Query Monitor to see what hooks are firing and what queries are running. Most issues come down to conflicts, missing dependencies, or PHP errors that are being hidden.

---

## Process

**Q: What's your process when you don't know something?**

> I research it. WordPress has excellent documentation, and I use the developer references frequently. If I'm stuck on something, I'll read the core source code to understand how a function actually works. I also maintain documentation for my own projects so I don't have to solve the same problem twice.

---

## The Hooks Question (Own It)

**Q: Tell me about a mistake you made and how you handled it.**

> In my recent interview with your team, I blanked on the term 'hooks' even though I use them daily. I call them functions in my head, not hooks. I could have made excuses, but instead I followed up to demonstrate that I do understand the concepts - I just had a terminology disconnect under pressure. That's why I'm here - I want to show you what I actually know.

---

## Closing

**Q: Do you have any questions for us?**

> [Ask your prepared questions, then close strong:]
> Thank you for speaking with me again. Based on our conversation, I know I have the battle-tested experience and the progression to be a valuable member of this team.

---

**Q: What's your availability?**

> My current contract is wrapping up. I can be available within 1-2 weeks depending on NASA's timeline and onboarding requirements.

---

## Quick Reference - If You Blank

| If they say... | You mean...                        |
| -------------- | ---------------------------------- |
| Hooks          | Spots to plug in my code           |
| Action         | Run code when X happens            |
| Filter         | Modify data before it's used       |
| Enqueue        | Load CSS/JS the WordPress way      |
| Transient      | Temporary cached data              |
| Sanitize       | Clean input before saving          |
| Escape         | Make output safe before displaying |

---

## Remember

1. You know this stuff - terminology tripped you up, not knowledge
2. Be specific - "I use `pre_get_posts`" beats "I modify queries"
3. Lead with outcomes - "Users enter their zip and see the 3 closest offices"
4. Technical details on request - don't dump jargon upfront
5. Close strong - "I know" not "I feel"
