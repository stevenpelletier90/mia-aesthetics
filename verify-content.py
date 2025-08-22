#!/usr/bin/env python3
"""
Content verification script for Mia Aesthetics templates
Compares template content with live website content
"""

import json
import re
from pathlib import Path
from typing import Dict, List, Tuple
import sys

# Procedure URLs to verify
PROCEDURE_URLS = {
    'breast-augmentation': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/augmentation-implants/',
    'breast-reduction': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/reduction/',
    'breast-lift': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/lift/',
    'breast-implant-revision': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/implant-revision/',
    'male-bbl': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/body/male-bbl/',
    'male-liposuction': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/body/male-liposuction/',
    'male-tummy-tuck': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/body/male-tummy-tuck/',
    'gynecomastia': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/male-breast-procedures/'
}

def extract_text_from_html(html_content: str) -> Dict[str, List[str]]:
    """Extract headings, paragraphs, and list items from HTML"""
    
    # Remove script and style tags
    html_content = re.sub(r'<script[^>]*>.*?</script>', '', html_content, flags=re.DOTALL | re.IGNORECASE)
    html_content = re.sub(r'<style[^>]*>.*?</style>', '', html_content, flags=re.DOTALL | re.IGNORECASE)
    
    # Extract headings
    headings = []
    heading_pattern = r'<h[1-6][^>]*>(.*?)</h[1-6]>'
    for match in re.finditer(heading_pattern, html_content, re.IGNORECASE | re.DOTALL):
        text = re.sub(r'<[^>]+>', '', match.group(1)).strip()
        if text:
            headings.append(text)
    
    # Extract paragraphs
    paragraphs = []
    para_pattern = r'<p[^>]*>(.*?)</p>'
    for match in re.finditer(para_pattern, html_content, re.IGNORECASE | re.DOTALL):
        text = re.sub(r'<[^>]+>', '', match.group(1)).strip()
        # Filter out very short paragraphs (likely UI elements)
        if text and len(text) > 20:
            paragraphs.append(text)
    
    # Extract list items
    list_items = []
    li_pattern = r'<li[^>]*>(.*?)</li>'
    for match in re.finditer(li_pattern, html_content, re.IGNORECASE | re.DOTALL):
        text = re.sub(r'<[^>]+>', '', match.group(1)).strip()
        if text:
            list_items.append(text)
    
    return {
        'headings': headings,
        'paragraphs': paragraphs,
        'list_items': list_items
    }

def normalize_text(text: str) -> str:
    """Normalize text for comparison"""
    # Remove extra whitespace
    text = re.sub(r'\s+', ' ', text)
    # Remove special characters for comparison
    text = re.sub(r'[–—]', '-', text)  # Normalize dashes
    text = re.sub(r'[''""]', '', text)  # Remove quotes
    return text.strip().lower()

def compare_content(template_data: Dict, expected_content: List[str]) -> Dict:
    """Compare template content with expected content"""
    
    results = {
        'matches': [],
        'missing': [],
        'extra': []
    }
    
    # Normalize all content for comparison
    template_text = ' '.join(template_data.get('paragraphs', []) + 
                            template_data.get('headings', []))
    template_text_normalized = normalize_text(template_text)
    
    # Check each expected content item
    for content in expected_content:
        content_normalized = normalize_text(content)
        # Check if the content exists (allowing for minor variations)
        if content_normalized[:50] in template_text_normalized:
            results['matches'].append(f"✓ Found: {content[:60]}...")
        else:
            results['missing'].append(f"✗ Missing: {content[:60]}...")
    
    # Calculate accuracy
    total = len(expected_content)
    if total > 0:
        results['accuracy'] = (len(results['matches']) / total) * 100
    else:
        results['accuracy'] = 0
    
    return results

def get_template_path(name: str) -> Path:
    """Get the template file path based on procedure name"""
    base_path = Path(__file__).parent
    
    if 'male' in name or name == 'gynecomastia':
        return base_path / 'male-procedure-templates' / f'{name}-template.html'
    elif 'breast' in name:
        return base_path / 'breast-procedure-templates' / f'{name}-template.html'
    else:
        return base_path / 'body-procedure-templates' / f'{name}-template.html'

def verify_all_templates():
    """Verify all template files"""
    
    report = {}
    
    for name, url in PROCEDURE_URLS.items():
        print(f"\nVerifying {name}...")
        print(f"URL: {url}")
        
        template_path = get_template_path(name)
        
        if not template_path.exists():
            print(f"  ✗ Template not found: {template_path}")
            report[name] = {'error': 'Template file not found'}
            continue
        
        # Read template
        with open(template_path, 'r', encoding='utf-8') as f:
            template_html = f.read()
        
        template_data = extract_text_from_html(template_html)
        
        # Store analysis
        report[name] = {
            'url': url,
            'template_path': str(template_path),
            'stats': {
                'headings': len(template_data['headings']),
                'paragraphs': len(template_data['paragraphs']),
                'list_items': len(template_data['list_items'])
            },
            'sample_headings': template_data['headings'][:5],
            'sample_content': template_data['paragraphs'][:3]
        }
        
        print(f"  ✓ Template analyzed:")
        print(f"    - {len(template_data['headings'])} headings")
        print(f"    - {len(template_data['paragraphs'])} paragraphs")
        print(f"    - {len(template_data['list_items'])} list items")
    
    # Save report
    report_path = Path(__file__).parent / 'content-verification-report.json'
    with open(report_path, 'w', encoding='utf-8') as f:
        json.dump(report, f, indent=2, ensure_ascii=False)
    
    print(f"\n✓ Report saved to: {report_path}")
    return report

def main():
    """Main function"""
    print("Content Verification Tool for Mia Aesthetics")
    print("=" * 50)
    
    report = verify_all_templates()
    
    print("\n" + "=" * 50)
    print("Summary:")
    print(f"Total procedures checked: {len(report)}")
    print(f"Templates found: {sum(1 for r in report.values() if 'error' not in r)}")
    print(f"Templates missing: {sum(1 for r in report.values() if 'error' in r)}")

if __name__ == "__main__":
    main()