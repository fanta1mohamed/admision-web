<template>
    <div class="seat-map">
      <div v-for="(group, index) in seatGroups" :key="index" class="seat-group">
        <!-- <div class="seat-element">
          <label for="rows">Filas:</label>
          <input type="number" v-model="group.rows" min="1" max="10">
          <label for="cols">Columnas:</label>
          <input type="number" v-model="group.cols" min="1" max="10">
        </div> -->
        <div v-for="row in group.rows" :key="row" class="seat-row">
          <div v-for="col in group.cols" :key="col" class="seat" :class="{ selected: isSeatSelected(index + 1, row, col) }">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        seatGroups: [
          { rows: 4, cols: 10 },
          { rows: 4, cols: 10 },
          { rows: 4, cols: 10 },
          { rows: 4, cols: 10 },
          { rows: 4, cols: 10 },
          { rows: 4, cols: 10 },
          { rows: 4, cols: 10 },
          { rows: 4, cols: 10 },
          // Agrega más grupos de asientos aquí
        ],
        selectedSeats: [
          { group: 2, row: 1, col: 2 },
          { group: 3, row: 3, col: 4 }
          // Agrega más asientos seleccionados aquí
        ]
      };
    },
    methods: {
      isSeatSelected(group, row, col) {
        return this.selectedSeats.some(seat => seat.group === group && seat.row === row && seat.col === col);
      }
    }
  };
  </script>
  
  <style>
  .seat-map {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
  }
  
  .seat-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    margin: 10px 10px;
  }
  
  .seat-element {
    display: flex;
    gap: 10px;
  }
  
  .seat-row {
    display: flex;
    justify-content: center;
    gap: 10px;
  }
  
  .seat {
    width: 25px;
    height: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #ccc;
    /* background-color: #ccc; */
  }
  
  .selected {
    color: #007bff;
  }
</style>








