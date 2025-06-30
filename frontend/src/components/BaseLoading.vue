<template>
    <li
        class="task-item p-4 border-l-4 border-l-gray-200 hover:bg-gray-50 transition animate-pulse"
    >
        <div class="flex items-center justify-between">
            <div class="flex items-center flex-1 min-w-0 space-x-3">
                <div class="rounded border border-gray-300 bg-gray-200 h-4 w-4 flex-shrink-0"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-4 rounded bg-gray-200 w-3/4"></div>
                    <div class="h-3 rounded bg-gray-200 w-1/2"></div>
                    <div class="h-3 rounded bg-gray-200 w-1/3 mt-2"></div>
                </div>
            </div>
            <div class="flex-shrink-0 flex items-center space-x-2 ml-4">
                <div class="h-4 w-4 rounded bg-gray-200"></div>
                <div class="h-4 w-4 rounded bg-gray-200"></div>
                <div class="h-4 w-4 rounded bg-gray-200"></div>
            </div>
        </div>
    </li>
</template>
