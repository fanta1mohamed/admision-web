<template>
    <div class="flex h-screen items-center justify-center bg-gray-200 px-6">
        <div class="w-full max-w-sm rounded-md bg-white pl-6 shadow-md">
            <n-config-provider preflight-style-disabled>
                <slot />
            </n-config-provider>
        </div>
    </div>
</template>
