Make sure tailwindcss is also compiled within you them with the following css:

```css
@import "tailwindcss";

/* Stat Cards */
.fi-card {
    @apply bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-4 border border-gray-200 dark:border-gray-700;
}

.fi-card-label {
    @apply text-xs font-medium text-gray-500 dark:text-gray-400 uppercase;
}

.fi-card-value {
    @apply mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100;
}

/* Success / Danger text */
.fi-text-success {
    @apply text-emerald-600;
}

.fi-text-danger {
    @apply text-red-600;
}

/* Panels (tables) */
.fi-panel {
    @apply bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-4 border border-gray-200 dark:border-gray-700;
}

.fi-panel-heading {
    @apply text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4;
}

/* Tables */
.fi-table {
    @apply min-w-full text-sm;
}

.fi-table th {
    @apply text-left text-gray-500 dark:text-gray-400 py-2 pr-4 border-b border-gray-200 dark:border-gray-700;
}

.fi-table td {
    @apply py-2 pr-4 text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700;
}

.fi-text-center {
    @apply text-center text-gray-500 dark:text-gray-400;
}

.fi-text-right {
    @apply text-right;
}

/* Stats inside panel */
.fi-stat-label {
    @apply text-sm text-gray-500 dark:text-gray-400;
}

.fi-stat-value {
    @apply text-xl font-semibold;
}
```
