<template>
    <div class="flex flex-col items-center justify-center min-h-screen text-center p-4">
        <h1 class="text-3xl font-bold text-heading">
            404 - Page Not Found
        </h1>
        <p class="mt-2 text-gray-500">
            Sorry, we couldn’t find that page.
        </p>
        <router-link
            to="/"
            class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-primary hover:bg-accent transition-colors"
        >
            Go back home
        </router-link>
    </div>
</template>
