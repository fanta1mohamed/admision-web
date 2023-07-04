<template>
  <div class="flex">
    <div>
      <table>
        <tr v-for="(fila, indexFila) in matriz" :key="indexFila">
          <td v-for="(registro, indexColumna) in fila" :key="indexColumna"
              class="registro"
              :class="{ active: registro }"
              @dragover.prevent @drop="onDrop(indexFila, indexColumna)">
            <div v-if="registro" style="font-size: .6rem;">
              {{ registro.primer_apellido }} {{ registro.segundo_apellido }} - {{ registro.distrito }}
              <button @click="eliminarRegistro(indexFila, indexColumna)">Eliminar</button>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div>
      <div v-for="(registro, index) in registros" :key="index" class="registro" draggable="true" @dragstart="onDragStart(registro)">
        {{ registro.primer_apellido }} {{ registro.segundo_apellido }} - {{ registro.distrito }}
        <!-- <button @click="eliminarRegistro(null, null, registro)">Eliminar</button> -->
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue';

const registrosOriginal = [
  { id: 1, primer_apellido:'Maron', segundo_apellido:'alvares',  distrito: 'Ilave' },
  { id: 2, primer_apellido:'Maron', segundo_apellido:'Ticona',  distrito: 'Puno' },
  { id: 3, primer_apellido:'Mamani', segundo_apellido:'Condori',  distrito: 'Acora'},
  { id: 4, primer_apellido:'Mamani', segundo_apellido:'Mamani',  distrito: 'Juliaca' },
  { id: 5, primer_apellido:'Ticona', segundo_apellido:'Arazola',  distrito: 'Puno' },
  { id: 6, primer_apellido:'Maquera', segundo_apellido:'Mamani',  distrito: 'Juli' },
  { id: 7, primer_apellido:'Maquera', segundo_apellido:'Turpo',  distrito: 'Ilave' },
  { id: 8, primer_apellido:'Luque', segundo_apellido:'Cusacani',  distrito: 'Juliaca' },
  { id: 9, primer_apellido:'Arazola', segundo_apellido:'Pilco',  distrito: 'Huancane' },
  { id: 10, primer_apellido:'Quispe', segundo_apellido:'Puma',  distrito: 'Moho' },
  { id: 11, primer_apellido:'Maquera', segundo_apellido:'Maquera',  distrito: 'Plateria' },
  { id: 12, primer_apellido:'Larijo', segundo_apellido:'Mamani',  distrito: 'Desaguadero' },
  { id: 13, primer_apellido:'Callacondo', segundo_apellido:'Chino',  distrito: 'Puno' },
  { id: 14, primer_apellido:'Chura', segundo_apellido:'Ticona',  distrito: 'Sandia' },
  { id: 15, primer_apellido:'Arratia', segundo_apellido:'Mamani',  distrito: 'Cusco' },
  { id: 16, primer_apellido:'Medina', segundo_apellido:'Medina',  distrito: 'Mazocruz' },
  { id: 17, primer_apellido:'Canales', segundo_apellido:'Ticona',  distrito: 'Acora' },
  { id: 18, primer_apellido:'Maron', segundo_apellido:'Maron',  distrito: 'Puno' },
  { id: 19, primer_apellido:'Carcausto', segundo_apellido:'Jumari',  distrito: 'Ilave' },
  { id: 20, primer_apellido:'Ticona', segundo_apellido:'Mamani',  distrito: 'Huancane' },
  { id: 21, primer_apellido:'Arazola', segundo_apellido:'Condori',  distrito: 'Pomata' },
  { id: 22, primer_apellido:'Barrientos', segundo_apellido:'Huanaco',  distrito: 'Zepita' },
  { id: 23, primer_apellido:'Sucasaire', segundo_apellido:'Tipo',  distrito: 'Plateria' },
  { id: 24, primer_apellido:'Tipo', segundo_apellido:'Mamani',  distrito: 'Chucuito' },
  { id: 25, primer_apellido:'Borda', segundo_apellido:'Almendra',  distrito: 'Salcedo' },
  { id: 26, primer_apellido:'Luque', segundo_apellido:'Mamani',  distrito: 'Sandia' },
  { id: 27, primer_apellido:'Jalanoca', segundo_apellido:'Calizaya',  distrito: 'Sandia' },
  { id: 28, primer_apellido:'Calizaya', segundo_apellido:'Herrera',  distrito: 'Arequipa' },
  { id: 29, primer_apellido:'Mijares', segundo_apellido:'Quipe',  distrito: 'Lima' },
  { id: 30, primer_apellido:'Gutierrez', segundo_apellido:'Condori',  distrito: 'Lima' },
  { id: 31, primer_apellido:'Chambilla', segundo_apellido:'Ticona',  distrito: 'Tacna' },
  { id: 32, primer_apellido:'Zeballos', segundo_apellido:'Maquera',  distrito: 'Yunguyo' },
  { id: 33, primer_apellido:'Chambi', segundo_apellido:'Mamani',  distrito: 'Ayaviri' },
  { id: 34, primer_apellido:'Churata', segundo_apellido:'Chura',  distrito: 'Ñunoa' },
  { id: 35, primer_apellido:'Jimenez', segundo_apellido:'Ampuero',  distrito: 'Ayaviri' },
  { id: 36, primer_apellido:'Ramos', segundo_apellido:'Paredes',  distrito: 'Ilave' },
  { id: 37, primer_apellido:'Carita', segundo_apellido:'Maron',  distrito: 'Lima' },
  { id: 38, primer_apellido:'Perez', segundo_apellido:'Aguilar',  distrito: 'Ayaviri' },
  { id: 39, primer_apellido:'Huanaco', segundo_apellido:'Zapana',  distrito: 'Ilave' },
  { id: 40, primer_apellido:'Chambi', segundo_apellido:'Condori',  distrito: 'Acora' }
];

const matriz = ref([
  [null, null, null, null],
  [null, null, null, null],
  [null, null, null, null],
  [null, null, null, null],
  [null, null, null, null],
  [null, null, null, null],
  [null, null, null, null],
  [null, null, null, null],
  [null, null, null, null],
  [null, null, null, null],

]);

const registros = ref([...registrosOriginal]);

function onDragStart(registro) {
  event.dataTransfer.setData('registro', JSON.stringify(registro));
}

function onDrop(fila, columna) {
  const registro = JSON.parse(event.dataTransfer.getData('registro'));

  if (registro) {
    if (matriz.value[fila][columna]) {
      registros.value.push(matriz.value[fila][columna]);
    }
    matriz.value[fila][columna] = registro;
    const index = registros.value.findIndex(item => item.id === registro.id);
    if (index !== -1) {
      registros.value.splice(index, 1);
    }
  }
}

function eliminarRegistro(fila, columna, registro) {
  if (fila !== null && columna !== null) {
    const temp = matriz.value[fila][columna];
    if (temp) {
      registros.value.push(temp);
      matriz.value[fila][columna] = null;
    }
  } else {
    const index = registros.value.findIndex(item => item.id === registro.id);
    if (index !== -1) {
      registros.value.splice(index, 1);
    }
  }
}
</script>

<style scoped>
table {
  border-collapse: collapse;
}

td {
  width: 100px;
  height: 50px;
  border: 3px solid white;
  background: wheat;
  text-align: center;
  vertical-align: middle;
}

.registro {
  margin: 2px;
  padding: 0px;
  background: #dddddd;
  font-size: .8rem;
  cursor:move;
}

.active {
  background-color: lightblue;
}
</style>
