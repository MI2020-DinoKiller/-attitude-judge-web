var app5 = new Vue({
    el: '#app',
    data: {
        isActive: false
    },
    methods: {
        submitForm: function () {
            this.isActive = !this.isActive;
        }
    }
})