<template>
    <div>
      <video ref="videoElement" autoplay></video>
      <canvas ref="canvasElement" style="display: none;"></canvas>
      <button @click="capturePhoto">Tomar foto</button>
    </div>
  </template>

<script>
export default {
  mounted() {
    // Acceder a la cámara
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        const videoElement = this.$refs.videoElement;
        videoElement.srcObject = stream;
        videoElement.play();
      })
      .catch(error => {
        console.error('Error al acceder a la cámara: ', error);
      });
  },
  methods: {
    capturePhoto() {
      const videoElement = this.$refs.videoElement;
      const canvasElement = this.$refs.canvasElement;
      const context = canvasElement.getContext('2d');

      // Establecer el tamaño del lienzo
      canvasElement.width = videoElement.videoWidth;
      canvasElement.height = videoElement.videoHeight;

      // Dibujar la imagen del video en el lienzo
      context.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);

      // Obtener la imagen como base64
      const base64Image = canvasElement.toDataURL('image/png');
      
      // Enviar la imagen a Laravel o hacer cualquier otra acción
      // Aquí puedes usar Axios o cualquier otra biblioteca de manejo de solicitudes HTTP
      // Ejemplo:
      axios.post('/guardar-imagen', { image: base64Image })
        .then(response => {
          console.log('Imagen guardada correctamente');
        })
        .catch(error => {
          console.error('Error al guardar la imagen: ', error);
        });
    }
  }
}
</script>
