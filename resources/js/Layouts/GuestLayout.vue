<template>
    <div class="flex h-screen items-center justify-center bg-gray-200 loginfondo" > 
        <div class="w-full rounded-md bg-white pl-6 login">
            <div class="rounded-xl bg-white md:border md:border-gray-300 sm:px-4 md:px-6 py-14">
                <slot />
            </div>
        </div>
    </div>
</template>
<style scoped>
.login{
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0px 0px;
    transform: translateY(-5px);
    min-height: 470px;
    background:none;
}

.loginfondo{
    background: linear-gradient(#ffffff, #e0e0e0da);
    opacity: 1.;
    background-size: 100% 100%;
}

@media (max-width: 600px) {
    .loginfondo{
        background-image: none;
        background: white;
    }
    .login{
        height: 100vh;  
        box-shadow: none;
        width: 100%;
    }
}

</style>