<template>
    <div>
        <div class="row">
            <!-- Tabs with Icon starts -->
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Listener</h4>
                        <button style="margin-top: 7px;" @click="addListener()" class="btn btn-primary">Add Listener</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="#">
                                    <div class="d-flex" v-for="(value,index) in listener" :key="index">
                                        <div class="form-group">
                                            <label for="label">Label</label>
                                            <input type="text" id="label" v-model="listener.label" placeholder="tranparent_hht.default" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="port">Port</label>
                                            <input type="number" id="port" v-model="listener.port" placeholder="3214" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="listener">Listener</label>
                                            <select id="listener" class="form-control" v-model="listener.listener">
                                                <option value="Transparent HTTP Bridge">Transparent HTTP Bridge</option>
                                                <option value="Transparent HTTP Router">Transparent HTTP Router</option>
                                                <option value="Transparent HTTP Bridge">Transparent HTTP Bridge</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-radio" style="margin-top: 29px;">
                                                <input type="radio" v-model="listener.ssl_intercept" id="customRadio1" name="customRadio" class="custom-control-input" checked />
                                                <label class="custom-control-label" for="customRadio1">SSL Intercept</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-radio" style="margin-top: 29px;">
                                                <input type="radio" v-model="listener.ssl_intercept" id="customRadio2" name="customRadio" class="custom-control-input" />
                                                <label class="custom-control-label" for="customRadio2">ANY</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input style="margin-top: 22px;" type="text" v-model="listener.any" class="form-control" placeholder="IPV4">
                                        </div>
                                        <div class="form-group">
                                            <button v-show="index || ( !index && listener_count > 1)" style="margin-top: 16px;" @click="remove(index)" class="btn"><i style="font-size: 22px; color: red;" class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" style="float: right;" type="submit">Save</button>
                                </form>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            listener: [{
                label: '',
                port: '',
                listener: '',
                ssl_intercept: '',
                any: ''
            }],
            listener_count: 1
        };
    },
    created() {
        var _this = this;
        
    },

    mounted() {
        
    },
    
    methods: {

        addListener () {
            this.listener.push({
                label: '',
                port: '',
                listener: '',
                ssl_intercept: '',
                any: ''
            })
            this.listener_count = this.listener_count + 1
        },

        remove (index) {
            this.listener.splice(index, 1)
            this.listener_count = this.listener_count - 1
        },
            
    },

    computed: {
        
    }
}
</script>

<style scoped>
    .form-group {
        margin-right: 10px;
    }
</style>