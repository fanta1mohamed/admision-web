<template>
    <a-card style="margin-bottom: 20px;">
        <div style="margin-top: -35px;">
            <a-tag v-if="datos.modalidad == 'CEPREUNA'" color="cyan" class="tagExamen" style="padding: 4px;">EXAMEN {{ datos.modalidad }}</a-tag>
            <a-tag v-if="datos.modalidad == 'GENERAL'" color="orange" class="tagExamen" style="padding: 4px;">EXAMEN {{ datos.modalidad }}</a-tag>
        </div>
        
        <div>
            

            <div style="display: flex; justify-content: flex-end; margin-top: -10px;">
                <!-- <div>
                    <a-label>Ingreso: </a-label> <span> {{ datos.apto }}</span>
                </div> -->

                <div>
                    <a-label>Fecha: </a-label> <span> {{ datos.fecha }}</span>
                </div>
            </div>

            <div style="display: flex; justify-content: center;">
                <div>
                    <span style="font-size: 40pt; font-weight: bold;">{{ datos.puntaje }}</span>
                </div>
            </div> 
            <div style="display: flex; justify-content: center; margin-top: -10px;">
                <div>
                    <a-label>Ingreso: </a-label> <span> {{ datos.apto }}</span>
                </div>
            </div>
            
        </div>
    </a-card>
</template>

<script setup>
const props = defineProps(['datos'])
</script>
<style scoped>
.tagExamen{ font-size: 1.2rem; }
@media (max-width: 600px) {
    .tagExamen{ font-size: .9rem; }
}

</style>