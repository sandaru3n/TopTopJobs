/**
 * Color Utility Functions for Categories and Subcategories
 * Provides consistent, unique colors for each category and subcategory
 */

// Extended color palette for categories and subcategories
const COLOR_PALETTE = [
    { name: 'blue', bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-700 dark:text-blue-400', border: 'border-blue-300 dark:border-blue-700' },
    { name: 'indigo', bg: 'bg-indigo-100 dark:bg-indigo-900/30', text: 'text-indigo-700 dark:text-indigo-400', border: 'border-indigo-300 dark:border-indigo-700' },
    { name: 'purple', bg: 'bg-purple-100 dark:bg-purple-900/30', text: 'text-purple-700 dark:text-purple-400', border: 'border-purple-300 dark:border-purple-700' },
    { name: 'pink', bg: 'bg-pink-100 dark:bg-pink-900/30', text: 'text-pink-700 dark:text-pink-400', border: 'border-pink-300 dark:border-pink-700' },
    { name: 'rose', bg: 'bg-rose-100 dark:bg-rose-900/30', text: 'text-rose-700 dark:text-rose-400', border: 'border-rose-300 dark:border-rose-700' },
    { name: 'red', bg: 'bg-red-100 dark:bg-red-900/30', text: 'text-red-700 dark:text-red-400', border: 'border-red-300 dark:border-red-700' },
    { name: 'orange', bg: 'bg-orange-100 dark:bg-orange-900/30', text: 'text-orange-700 dark:text-orange-400', border: 'border-orange-300 dark:border-orange-700' },
    { name: 'amber', bg: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-700 dark:text-amber-400', border: 'border-amber-300 dark:border-amber-700' },
    { name: 'yellow', bg: 'bg-yellow-100 dark:bg-yellow-900/30', text: 'text-yellow-700 dark:text-yellow-400', border: 'border-yellow-300 dark:border-yellow-700' },
    { name: 'lime', bg: 'bg-lime-100 dark:bg-lime-900/30', text: 'text-lime-700 dark:text-lime-400', border: 'border-lime-300 dark:border-lime-700' },
    { name: 'green', bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-700 dark:text-green-400', border: 'border-green-300 dark:border-green-700' },
    { name: 'emerald', bg: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-700 dark:text-emerald-400', border: 'border-emerald-300 dark:border-emerald-700' },
    { name: 'teal', bg: 'bg-teal-100 dark:bg-teal-900/30', text: 'text-teal-700 dark:text-teal-400', border: 'border-teal-300 dark:border-teal-700' },
    { name: 'cyan', bg: 'bg-cyan-100 dark:bg-cyan-900/30', text: 'text-cyan-700 dark:text-cyan-400', border: 'border-cyan-300 dark:border-cyan-700' },
    { name: 'sky', bg: 'bg-sky-100 dark:bg-sky-900/30', text: 'text-sky-700 dark:text-sky-400', border: 'border-sky-300 dark:border-sky-700' },
    { name: 'violet', bg: 'bg-violet-100 dark:bg-violet-900/30', text: 'text-violet-700 dark:text-violet-400', border: 'border-violet-300 dark:border-violet-700' },
    { name: 'fuchsia', bg: 'bg-fuchsia-100 dark:bg-fuchsia-900/30', text: 'text-fuchsia-700 dark:text-fuchsia-400', border: 'border-fuchsia-300 dark:border-fuchsia-700' },
    { name: 'slate', bg: 'bg-slate-100 dark:bg-slate-900/30', text: 'text-slate-700 dark:text-slate-400', border: 'border-slate-300 dark:border-slate-700' },
    { name: 'zinc', bg: 'bg-zinc-100 dark:bg-zinc-900/30', text: 'text-zinc-700 dark:text-zinc-400', border: 'border-zinc-300 dark:border-zinc-700' },
    { name: 'neutral', bg: 'bg-neutral-100 dark:bg-neutral-900/30', text: 'text-neutral-700 dark:text-neutral-400', border: 'border-neutral-300 dark:border-neutral-700' },
    { name: 'stone', bg: 'bg-stone-100 dark:bg-stone-900/30', text: 'text-stone-700 dark:text-stone-400', border: 'border-stone-300 dark:border-stone-700' },
    { name: 'gray', bg: 'bg-gray-100 dark:bg-gray-700', text: 'text-gray-700 dark:text-gray-300', border: 'border-gray-300 dark:border-gray-600' }
];

/**
 * Simple hash function to convert string to number
 */
function hashString(str) {
    let hash = 0;
    if (str.length === 0) return hash;
    for (let i = 0; i < str.length; i++) {
        const char = str.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash; // Convert to 32bit integer
    }
    return Math.abs(hash);
}

/**
 * Get unique color for a category or subcategory based on its name
 */
function getUniqueColor(name) {
    if (!name) {
        return COLOR_PALETTE[COLOR_PALETTE.length - 1]; // gray as fallback
    }
    const hash = hashString(name);
    const index = hash % COLOR_PALETTE.length;
    return COLOR_PALETTE[index];
}

/**
 * Get color classes for category/subcategory badge
 */
function getColorClasses(name) {
    const color = getUniqueColor(name);
    return `${color.bg} ${color.text}`;
}

/**
 * Predefined category colors (for consistency with existing design)
 */
const CATEGORY_COLORS = {
    'IT & Software': { bg: 'bg-indigo-100 dark:bg-indigo-900/30', text: 'text-indigo-700 dark:text-indigo-400' },
    'Marketing & Advertising': { bg: 'bg-pink-100 dark:bg-pink-900/30', text: 'text-pink-700 dark:text-pink-400' },
    'Sales': { bg: 'bg-orange-100 dark:bg-orange-900/30', text: 'text-orange-700 dark:text-orange-400' },
    'Customer Service': { bg: 'bg-teal-100 dark:bg-teal-900/30', text: 'text-teal-700 dark:text-teal-400' },
    'Finance & Accounting': { bg: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-700 dark:text-emerald-400' },
    'Engineering': { bg: 'bg-cyan-100 dark:bg-cyan-900/30', text: 'text-cyan-700 dark:text-cyan-400' },
    'Design & Creative': { bg: 'bg-rose-100 dark:bg-rose-900/30', text: 'text-rose-700 dark:text-rose-400' },
    'Healthcare & Medical': { bg: 'bg-red-100 dark:bg-red-900/30', text: 'text-red-700 dark:text-red-400' },
    'Education & Training': { bg: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-700 dark:text-amber-400' },
    'Hospitality & Tourism': { bg: 'bg-yellow-100 dark:bg-yellow-900/30', text: 'text-yellow-700 dark:text-yellow-400' },
    'Logistics & Supply Chain': { bg: 'bg-lime-100 dark:bg-lime-900/30', text: 'text-lime-700 dark:text-lime-400' },
    'Construction & Skilled Trades': { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-700 dark:text-green-400' },
    'Human Resources': { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-700 dark:text-blue-400' },
    'Legal': { bg: 'bg-violet-100 dark:bg-violet-900/30', text: 'text-violet-700 dark:text-violet-400' },
    'Media, Writing & Communications': { bg: 'bg-fuchsia-100 dark:bg-fuchsia-900/30', text: 'text-fuchsia-700 dark:text-fuchsia-400' },
    'Manufacturing & Production': { bg: 'bg-slate-100 dark:bg-slate-900/30', text: 'text-slate-700 dark:text-slate-400' },
    'Real Estate': { bg: 'bg-sky-100 dark:bg-sky-900/30', text: 'text-sky-700 dark:text-sky-400' },
    'Retail': { bg: 'bg-purple-100 dark:bg-purple-900/30', text: 'text-purple-700 dark:text-purple-400' },
    'Agriculture & Environment': { bg: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-700 dark:text-emerald-400' },
    'Security & Armed Forces': { bg: 'bg-zinc-100 dark:bg-zinc-900/30', text: 'text-zinc-700 dark:text-zinc-700' },
    'Administrative & Office': { bg: 'bg-neutral-100 dark:bg-neutral-900/30', text: 'text-neutral-700 dark:text-neutral-400' },
    'Creative, Entertainment & Arts': { bg: 'bg-pink-100 dark:bg-pink-900/30', text: 'text-pink-700 dark:text-pink-400' }
};

/**
 * Get color for category (uses predefined or hash-based)
 */
function getCategoryColor(categoryName) {
    if (!categoryName) {
        return 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
    }
    const predefined = CATEGORY_COLORS[categoryName];
    if (predefined) {
        return `${predefined.bg} ${predefined.text}`;
    }
    return getColorClasses(categoryName);
}

/**
 * Get color for subcategory (always hash-based for uniqueness)
 */
function getSubcategoryColor(subcategoryName) {
    if (!subcategoryName) {
        return 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
    }
    return getColorClasses(subcategoryName);
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        getCategoryColor,
        getSubcategoryColor,
        getColorClasses,
        getUniqueColor
    };
}

