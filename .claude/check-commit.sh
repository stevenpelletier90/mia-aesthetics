#!/bin/bash

echo "🚨 **THEME COMMIT CHECKLIST** 🚨"
echo ""
echo "You are about to commit changes to the Mia Aesthetics WordPress theme. Before proceeding, review:"
echo ""
echo "**Code Quality:**"
echo "✅ Semantic HTML used, no redundant <div>s or <span>s?"
echo "✅ Bootstrap utilities used WHERE APPROPRIATE (custom styles are fine when needed)?"
echo "✅ Custom styles properly scoped to avoid unintended overrides?"
echo "✅ No inline styles unless absolutely necessary?"
echo "✅ !important used sparingly and only when required?"
echo "✅ All custom styles properly namespaced (e.g., .mia-*, .testimonials-*, etc.)?"
echo "✅ No unused classes or dead code?"
echo ""
echo "**WordPress Compatibility:**"
echo "✅ Plugin-safe: no conflicts with WP Rocket, Imagify, Yoast, ACF Pro?"
echo "✅ Following WordPress coding standards?"
echo "✅ Template hierarchy respected?"
echo ""
echo "**Accessibility & Performance:**"
echo "✅ Accessible: alt tags, labels, ARIA attributes, keyboard focus?"
echo "✅ Component files are modular and reusable?"
echo "✅ Images optimized with proper paths?"
echo "✅ No console errors or warnings?"
echo ""
echo "**Testing:**"
echo "✅ Tested on mobile, tablet, and desktop?"
echo "✅ Cross-browser tested (Chrome, Firefox, Safari, Edge)?"
echo "✅ Passes Core Web Vitals/Lighthouse audit?"
echo "✅ No PHP errors with WP_DEBUG enabled?"
echo ""
echo "⚠️ **If ANY of these are not true, ABORT commit and resolve issues first.**"
echo ""
echo "Type CONFIRM to proceed with commit, or CANCEL to abort:"
read -p "> " response

if [[ "$response" != "CONFIRM" ]]; then
    echo "❌ Commit aborted. Please resolve issues first."
    exit 2  # Blocking error
fi

echo "✅ Proceeding with commit..."
exit 0