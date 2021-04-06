<template>
    <div class="tab-pane" id="dns" aria-labelledby="dns-tab" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">DNS</h4>
                <button style="margin-top: 7px;" @click="addDns()" class="btn btn-primary">Add DNS</button>
            </div>
            <div class="card-body">
                <form>
                    <div v-for="(value,index) in dns" :key="index">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="dns">DNS</label>
                                    <input class="form-control" type="text" id="dns" v-model="value.name" placeholder="198.168.252.2">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button v-show="index || ( !index && dns_count > 1)" style="margin-top: 16px;" @click="remove(index)" class="btn"><i style="font-size: 22px; color: red;" class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button style="float: right;" type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            dns: [{
                name: ''
            }],
            dns_count: 1
        };
    },
    created() {
        var _this = this;
        
    },

    mounted() {
        
    },
    
    methods: {

        addDns () {
            this.dns.push({
                name: ''
            })
            this.dns_count = this.dns_count + 1
        },

        remove (index) {
            this.dns.splice(index, 1)
            this.dns_count = this.dns_count - 1
        },
            
    },

    computed: {
        
    }
}
</script>