<template id="my-form">
    <div class="container">
        <div class="form-control">
            <input type="text" v-model="title">
        </div>
    </div>
</template>


<script>
export default {
    template : "#my-form",
    data() {
        return {
            title : '',
        }
    },
    methods : {
        submit(){
            // this.$eventHub.$emit('submit', this.title);
        }
    }
};
</script>
