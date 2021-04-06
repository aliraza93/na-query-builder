<template>
    <div class="tab-pane" id="bridges" aria-labelledby="bridges-tab" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Bridges</h4>
                <button style="margin-top: 7px;" @click="addBridge()" class="btn btn-primary">Add Bridge</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="#">
                            <div class="d-flex" v-for="(value,index) in bridges" :key="index">
                                <div class="form-group">
                                    <label for="bridge_ip">Bridge IP</label>
                                    <input type="text" id="bridge_ip" v-model="bridges.bridge_ip" placeholder="172.16.10.5/24" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="eth0">eth0</label>
                                    <input type="number" id="eth0" v-model="bridges.eth0" placeholder="eth0" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="eth1">eth1</label>
                                    <input type="number" id="eth1" v-model="bridges.eth1" placeholder="eth1" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="contains_routes">Contains Routes</label>
                                    <input type="number" id="contains_routes" v-model="bridges.contains_routes" placeholder="contains_routes" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="contains_gateway">Contains Gateway</label>
                                    <input type="number" id="contains_gateway" v-model="bridges.contains_gateway" placeholder="contains_gateway" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button style="margin-top: 16px;" class="btn"><i style="font-size: 22px;" class="fas fa-edit"></i></button>
                                </div>
                                <div class="form-group">
                                    <button v-show="index || ( !index && bridges_count > 1)" style="margin-top: 16px;" @click="remove(index)" class="btn"><i style="font-size: 22px; color: red;" class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <button class="btn btn-primary" style="float: right;" type="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            bridges: [{
                bridge_ip: '',
                eth0: '',
                eth1: '',
                contains_routes: '',
                contains_gateway: ''
            }],
            bridges_count: 1
        };
    },
    created() {
        var _this = this;
        
    },

    mounted() {
        
    },
    
    methods: {

        addBridge () {
            this.bridges.push({
                bridge_ip: '',
                eth0: '',
                eth1: '',
                contains_routes: '',
                contains_gateway: ''
            })
            this.bridges_count = this.bridges_count + 1
        },

        remove (index) {
            this.bridges.splice(index, 1)
            this.bridges_count = this.bridges_count - 1
        },
            
    },

    computed: {
        
    }
}
</script>