<template>
    <div class="flex h-screen items-center justify-center bg-gray-200 loginfondo" > 
        <div class="w-full rounded-md bg-white pl-6 shadow-md login">
            <div style="background: yellow; border-radius: 12px; background: white; border:solid 1px #9d9d9d" class="px-4 py-14">
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
    box-shadow: 0px 5px 10px 5px rgba(0, 0, 0, 0.11);
    transform: translateY(-5px);
    min-height: 470px;
    background:none;
}

.loginfondo{
    background-image: linear-gradient(#1722b993, #08093dda),                  
    url("../../assets/imagenes/ciudad_unap.png");
    opacity: 1.;
    background-size: 100% 100%;
}

@media (max-width: 600px) {
    .loginfondo{
        background-image: none;
        background: #0e587a;
        background: linear-gradient(#1722b9ad, #08093db2),
    }
    .login{
        height: 100vh;  
        box-shadow: none;
        width: 100%;
    }
}

</style>