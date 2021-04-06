<template>
    <div>
        <div class="row">
            <div class="col-md-2"></div>
            <!-- Tabs with Icon starts -->
            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Proxy CA</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="current_certificate"></label>
                                <input type="text" id="current_certificate" class="form-control" placeholder="Meshimer">
                            </div>
                            <div class="form-group">
                                <label for="active_certificate"></label>
                                <select id="active_certificate" class="form-control">
                                    <option value="Certificate Authority">Certificate Authority</option>
                                </select>
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