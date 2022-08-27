<template id="institutions">
    <div class="container">
        <div class="form-control">
            <label>Institutions</label>
            <select class="mb-3">
                <option value="">....</option>
                <option v-for='data in institutions' :value='data.chave'>{{ data.valor }}</option>
            </select>
        </div>
        <div class="mb-3">
            <button type="button" class="btn btn-primary" @click="getInstitutions()">Buscar instituições</button>
        </div>

    </div>
</template>

<script>

export default {
    data() {
        return {
            institutions: []
        }
    },
    methods: {
        getInstitutions: function () {
            axios.get('/api/institutions/')
                .then(function (response) {
                    this.institutions = response.data;
                }.bind(this));
        }
    }
}
</script>
