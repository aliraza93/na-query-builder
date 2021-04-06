<template>
    <div>
        <div class="row">
            <div class="col-md-2"></div>
            <!-- Tabs with Icon starts -->
            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">System Maintenance</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="d-flex">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary">Restart</button>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-danger">Power Off</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
            <div class="col-md-2"></div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {

        };
    },
    created() {
        var _this = this;
        
    },

    mounted() {
        
    },
    
    methods: {
            
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