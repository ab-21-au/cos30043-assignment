<template>
    <div class="d-flex flex-column flex-md-row gap-4 gap-md-0 p-3 min-vh-100">
        <aside class="dashboard-sidebar d-flex flex-column gap-2 flex-shrink-0 p-3 border rounded bg-body-tertiary">
            <RouterLink
                v-for="page in pages"
                :key="page.label"
                :to="page.to"
                class="dashboard-link btn btn-outline-primary w-100 text-start"
                exact-active-class="active"
            >
                {{ page.label }}
            </RouterLink>
        </aside>

        <main class="dashboard-content flex-grow-1 p-3 border rounded bg-body">
            <RouterView />
        </main>
    </div>
</template>

<script setup>
const pages = [
    { label: "Overview", to: { name: "account-overview" } },
    { label: "Stats", to: { name: "account-stats" } },
    { label: "Lists", to: { name: "account-lists" } },
    { label: "Reviews", to: { name: "account-reviews" } },
    { label: "Settings", to: { name: "account-settings" } },
]
</script>

<style scoped>
.dashboard-sidebar {
    width: 14rem;
}

.dashboard-link.active {
    position: relative;
    z-index: 1;
}

@media (min-width: 768px) {
    .dashboard-sidebar {
        border-right: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .dashboard-content {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .dashboard-link.active {
        border-right-color: transparent;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        margin-right: calc(-1 * var(--bs-border-width));
    }
}

@media (max-width: 767.98px) {
    .dashboard-sidebar {
        width: 100%;
    }

    .dashboard-link.active {
        border-bottom-color: transparent;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        margin-right: 0;
        margin-bottom: calc(-1 * var(--bs-border-width));
    }
}
</style>
