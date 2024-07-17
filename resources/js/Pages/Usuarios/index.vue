<template>
  <Head title="Usuarios"/>
  <AuthenticatedLayout>
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
  <!-- <pre>{{ permisos}}</pre> -->
  <a-button class="mb-3"  type="primary" @click="showModalRol">Nuevo</a-button>

  <a-table bordered  :data-source="users" :columns="columns" size="small" :pagination="paginationConfig">

    <template #bodyCell="{ column, index, record }">
    
      <template v-if="column.dataIndex === 'role_name'">
        <a-button primary size="small">{{users[index].role_name }}</a-button>
       </template>
      
      <template v-if="column.dataIndex === 'operation'">
        <a-button class="mr-1" type="primary" @click="editar(record)" style="border-radius: 4px; background: none; border: 1px solid gray;  color:gray;" size="small">
          <template #icon><LockOutlined/></template>
        </a-button>
        <a-button class="mr-1" type="primary" @click="editar(record)" style="border-radius: 4px; background: none; color:blue;" size="small">
          <template #icon><form-outlined/></template>
        </a-button>
        <a-button type="danger" style="border-radius: 4px; background: none; color:red;" size="small">
          <template #icon><delete-outlined /></template>
        </a-button>
      </template>
    </template>
  </a-table>

  </div>
  
  </AuthenticatedLayout>

  <div>
    <a-modal v-model:visible="visible" title="Nuevo Usuario" style="margin-top: -50px;" @ok="handleOk">
      <div>

        <a-form
          ref="formRef"
          name="custom-validation"
          :model="formState"
          :rules="rules"
          v-bind="layout"
          @finish="handleFinish"
          @validate="handleValidate"
          :validate-messages="validateMessages"
          @finishFailed="handleFinishFailed"
        >
          <a-form-item has-feedback label="Nombre" name="name">
            <a-input v-model:value="formState.name" type="text" autocomplete="off" />
          </a-form-item>

          <a-form-item has-feedback label="Apellido Paterno" name="paterno">
            <a-input v-model:value="formState.paterno" type="text" autocomplete="off" />
          </a-form-item>
          
          <a-form-item has-feedback label="Apellido Materno" name="materno">
            <a-input v-model:value="formState.materno" type="text" autocomplete="off" />
          </a-form-item>

          <a-form-item has-feedback label="Correo" name="email" :rules="[{ type: 'email', required: true }]">
            <a-input v-model:value="formState.email" type="text" autocomplete="off" />
          </a-form-item>

          <a-form-item has-feedback label="Contraseña" name="pass">
            <a-input v-model:value="formState.pass" type="password" autocomplete="off" />
          </a-form-item>
          <a-form-item has-feedback label="Confirmar Contraseña" name="checkPass">
            <a-input v-model:value="formState.checkPass" type="password" autocomplete="off" />
          </a-form-item>
          <a-form-item has-feedback label="Rol">
            <a-input value="Revisor" disabled/>
            <!-- <a-auto-complete
              v-model:value="rols"
              :options="roles"
              style="width: 100%;"
              :filter-option="filterOption"
              >
              <a-input
                placeholder="input here"
                class="custom"
                v-model:value="rols"
              />
              <template #option="item" >
                <div @click="cambiar(item)">
                  <span>
                    {{ item.value }}
                  </span>
                </div>
              </template>
            </a-auto-complete> -->
          </a-form-item>
        </a-form>
  
      </div>

      <template #footer>
        <a-button type="primary" @click="guardar()">Submit</a-button>
        <a-button style="margin-left: 10px" @click="resetForm">Reset</a-button>
      </template>
    </a-modal>
  </div>


  
</template>
  
<script setup>
  import { Head } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  import { computed, ref, reactive } from 'vue';
  import { FormOutlined, LockOutlined, DeleteOutlined, UnlockOutlined } from '@ant-design/icons-vue';
  import { notification } from 'ant-design-vue';
import Procesos from '../Procesos/procesos.vue';

  const props = defineProps(['usuarios'])
  
  const roles = ref([]);
  const rols = ref("");
  const rol = ref("");
  const users = ref([]);
  const permisos = ref([]);

  const visible = ref(false)
  const showModalRol = () => {
      visible.value = true;
  };
  const handleOk = e => {
      console.log(e)
      visible.value = false
  };

  const editar = (item) => {
    formState.id = item.id;
    formState.name = item.name;
    formState.paterno = item.paterno;
    formState.materno = item.materno;
    formState.email = item.email;
    formState.rol = item.id_rol;
    formState.id_proceso = item.id_proceso;
    formState.proceso = item.proceso;
    rol.value = item.id_rol;
    visible.value = true;
  }


    const formRef = ref();
    const formState = reactive({
      id:null,
      name:'',
      paterno:'',
      materno:'',
      email:'',
      pass: '',
      checkPass: '',
      rol: ref(null),
      id_proceso:null,
      proceso: 9
    });

    let validateCorreo = async (_rule, value) => {
      if (value === '') {
        return Promise.reject('Ingrese su correo electrónico');
      } else {
        return Promise.resolve();
      }
    };

    let validateNombre = async (_rule, value) => {
      if (value === '') {
        return Promise.reject('Ingrese su nombre');
      } else {
        return Promise.resolve();
      }
    };

    let validatePass = async (_rule, value) => {
      if (value === '') {
        return Promise.reject('Ingrese la contraseña');
      } else {
        if (formState.checkPass !== '') {
          formRef.value.validateFields('checkPass');
        }
        return Promise.resolve();
      }
    };

    let validatePass2 = async (_rule, value) => {
      if (value === '') {
        return Promise.reject('Ingrese la contraseña nuevamente');
      } else if (value !== formState.pass) {
        return Promise.reject("Las contraseñas no coenciden");
      } else {
        return Promise.resolve();
      }
    };

    const rules = {
      pass: [{
        required: true,
        validator: validatePass,
        trigger: 'change',
      }],
      checkPass: [{
        required: true,
        validator: validatePass2,
        trigger: 'change',
      }],
      name: [{
        required: true,
        validator: validateNombre,
        trigger: 'change',
      }],
      email: [{
        validator: validateCorreo,
        trigger: 'change',
      }],


    };

    const columns = [
      { title: 'Nombre', dataIndex: 'name',width: '30%',}, 
      { title: 'Correo', dataIndex: 'email',}, 
      { title: 'Rol', dataIndex: 'role_name', }, 
      { title: 'Proceso', dataIndex: 'proceso', }, 
      { title: 'operation', dataIndex: 'operation', }];


    const paginationConfig = { // Configuración de paginación
        pageSize: 20,
    }
    const layout = {
      labelCol: {
        span: 8,
      },
      wrapperCol: {
        span: 18,
      },
    };
    const handleFinish = values => {
      console.log(values, formState);
    };
    const handleFinishFailed = errors => {
      console.log(errors);
    };
    const resetForm = () => {
      formRef.value.resetFields();
    };
    const handleValidate = (...args) => {
      console.log(args);
    };

    const validateMessages = {
      required: 'Ingrese ${label}',
      types: {
        email: '${label} no valido',
      },
    };

    
    const value =  ref('')

    const options =  ref([
      { value: 'Burns Bay Road',}, 
      { value: 'Downing Street', }, 
      { value: 'Wall Street', }]);

    const filterOption = (input, option) => {
      return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };

    const getUsuarios = async () => {  
      let res = await axios.get(`get-usuarios`);
      users.value = res.data.usuarios;
    }

    const getRoles = async () => {  
      let res = await axios.get(`get-roles-u`);
      roles.value = res.data.datos;
    }

    const getPermisos = async () => {
      let res = await axios.get(`get-permisos`);
      permisos.value = res.data.permisos;
    }

    getRoles();
    getUsuarios();
    getPermisos()

    const cambiar = val =>
    { 
       rol.value = val.id;
    }

    const guardar = () => {
      let post = {
          id: formState.id,
          name:formState.name,
          paterno: formState.paterno,
          materno: formState.materno,
          email:formState.email,
          password:formState.pass,
          rol:2,
          id_proceso: 9

      };
      axios.post("save-user", post).then((result) => {
        console.log(result.data.user);
        visible.value = false;
        formState.id = null;
        formState.name = '';
        formState.paterno = '';
        formState.materno = '';
        formState.email = '';
        formState.pass =  '';
        formState.checkPass =  '';
        formState.rol =  ref(null);
        formState.id_proceso = null;
        formState.proceso =  9
        getUsuarios();
        notificacion('success','Nuevo Usuario Agregado', result.data.user.name );

      });


    }

    const notificacion = (type, titulo, mensaje) => {
      notification[type]({
        message: titulo,
        description: mensaje,
      });
    };


    const buscar = () => {
      if (permisos.value.find(e => e.id === 8)){return true}
      else{ return false};
    }
    
    console.log(buscar()) 




</script>