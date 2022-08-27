import './bootstrap';

import { createApp } from 'vue';
import MyList from "./components/my-list";
import Institutions from './components/Institutions';
import MyForm from "./components/my-form";



const app = createApp({});

app.component('my-list', MyList);
app.component('Institutions', Institutions);
app.component('my-form', MyForm);


app.mount('#app');
