<template id="my-list">
    <slot></slot>
    <div class="container">
        <div class="form-control">
            <ul class="list-group">
                <li v-for="item in listCars">{{ item.title }}</li>

            </ul>
        </div>
    </div>
    <slot name="footer"></slot>
</template>

<script>

export default {
    template : "#my-list",
    data() {
        return {
            listCars: [
                {title: "Gol gts"},
            ]
        }
    },
    created() {
        // const aux = this;
        //     app.events.submit(function (title){
        //         if (title){
        //             aux.listCars.push({title: title});
        //         }
        //     })
    }

};
</script>
