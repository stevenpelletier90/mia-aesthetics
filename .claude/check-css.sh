#!/bin/bash

echo "🚨 **CSS CONFLICT CHECK REQUIRED** 🚨"
echo ""
echo "**BEFORE WRITING ANY CSS:**"
echo ""
echo "1. **EXAMINE BASE.CSS FIRST:**"
echo "   • Check /assets/css/base.css for existing global styles"
echo "   • Review CSS custom properties (variables)"
echo "   • Check existing button variants, typography rules"
echo "   • Note z-index hierarchy to avoid conflicts"
echo ""
echo "2. **AVOID GLOBAL SELECTOR CONFLICTS:**"
echo "   • DON'T override: body, h1-h6, p, a, ul, ol without specific class scoping"
echo "   • DON'T redefine: .mia-button, .section-tagline, .container"
echo "   • DON'T change: --color-primary, --color-gold, --font-heading, --font-body"
echo "   • Use specific class names prefixed with section name (e.g., .testimonials-title)"
echo ""
echo "3. **BOOTSTRAP 5 COMPATIBILITY:**"
echo "   • Use Bootstrap utilities where possible (mb-5, text-center, etc.)"
echo "   • Don't override Bootstrap components without specific selectors"
echo "   • Respect Bootstrap's spacing system (rem-based)"
echo ""
echo "4. **CONSISTENCY CHECKS:**"
echo "   • Section padding: 5rem-6rem (3rem-4rem on mobile)"
echo "   • Title sizes: clamp(2.5rem, 5vw, 4rem)"
echo "   • Consistent hover effects and transitions"
echo "   • Mobile-first responsive design"
echo ""
echo "5. **TEMPLATE-SPECIFIC CSS:**"
echo "   • Keep styles in template-specific files (page-*.css)"
echo "   • Avoid !important unless absolutely necessary"
echo "   • Scope animations and special effects to avoid performance issues"
echo ""
echo "**Have you checked base.css and verified no conflicts? (Y/N)**"
read -p "> " response

if [[ "$response" != "Y" && "$response" != "y" ]]; then
    echo "❌ Please check base.css first before proceeding."
    exit 2  # Blocking error
fi

echo "✅ Proceeding with CSS changes..."
exit 0