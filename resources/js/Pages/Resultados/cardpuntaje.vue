<template>
    <a-card style="margin-bottom: 20px;">
        <div>
            
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <a-label>Apto: </a-label> <span> {{ datos.apto }}</span>
                </div>

                <div>
                    <a-label>Fecha: </a-label> <span> {{ datos.fecha }}</span>
                </div>
            </div>

            <div style="display: flex; justify-content: center;">
                <div style="font-size: 15pt; font-weight: bold;">
                    <a-label>EXAMEN</a-label> <span> {{ datos.modalidad }}</span>
                </div>
            </div>

            <div style="display: flex; justify-content: center;">
                <div>
                    <span>Puntaje </span>
                </div>
            </div>

            <div style="display: flex; justify-content: center;">
                <div style="font-size: 20pt; font-weight: bold;">
                    <span>{{ datos.puntaje }}</span>
                </div>
            </div> 
            <div style="display: flex; justify-content: center;">
                <div style="font-size: 12pt;">
                    <a-label>DNI: </a-label>
                    <span>{{ datos.dni }}</span> 
                </div>
            </div>
            <!-- <div style="display: flex; justify-content: flex-start;">
                <div>
                    <a-label>Nombres: </a-label>
                    <span>{{ datos.nombres }} {{ datos.paterno }} {{ datos.materno }}</span>
                </div>
            </div> -->
            
        </div>
    </a-card>
</template>

<script setup>
const props = defineProps(['datos'])

</script>