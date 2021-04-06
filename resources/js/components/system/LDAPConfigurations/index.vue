<template>
    <div>
        <div class="row">
            <!-- Tabs with Icon starts -->
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">LDAP Configurations</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="d-flex">
                                <div class="form-group">
                                    <div class="custom-control custom-radio" style="margin-top: 29px;">
                                        <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" checked />
                                        <label class="custom-control-label" for="customRadio1">Unsecure</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-radio" style="margin-top: 29px;">
                                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" />
                                        <label class="custom-control-label" for="customRadio2">Start TLS</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-radio" style="margin-top: 29px;">
                                        <input type="radio" id="customRadio3" name="customRadio" class="custom-control-input" />
                                        <label class="custom-control-label" for="customRadio3">TLS</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="schema">Schema</label>
                                        <input type="text" id="schema" class="form-control" placeholder="Active Directory">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" class="form-control" placeholder="user@example.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_prefix">Account Prefix</label>
                                        <input type="text" id="account_prefix" class="form-control" placeholder="Account Prefix">
                                    </div>
                                    <div class="form-group">
                                        <label for="host">Host</label>
                                        <input type="text" id="host" class="form-control" placeholder="127.195.15.1">
                                    </div>
                                    <div class="form-group">
                                        <label for="base_dn">Base DN</label>
                                        <input type="text" id="base_dn" class="form-control" placeholder="dc=meshimer">
                                    </div>
                                    <div class="form-group">
                                        <label for="filter_include">Filter Include</label>
                                        <input type="text" id="filter_include" class="form-control" placeholder="Filter Include">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="commited_usn">Highest Commited USN</label>
                                        <input type="text" id="commited_usn" class="form-control" placeholder="23451">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" class="form-control" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_suffix">Account Suffix</label>
                                        <input type="text" id="account_suffix" class="form-control" placeholder="Account Suffix">
                                    </div>
                                    <div class="form-group">
                                        <label for="port">Port</label>
                                        <input type="text" id="port" class="form-control" placeholder="784">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeout">Timeout</label>
                                        <input type="text" id="timeout" class="form-control" placeholder="100">
                                    </div>
                                    <div class="form-group">
                                        <label for="filter_exclude">Filter Exclude</label>
                                        <input type="text" id="filter_exclude" class="form-control" placeholder="Filter Exclude">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="custom_options">Custom Options</label>
                                <input type="text" id="custom_options" class="form-control" placeholder="Custom Options">
                            </div>
                            <div>
                                <button style="margin-top: 20px;" type="submit" class="btn btn-primary">Save</button>
                                <button style="margin-top: 20px; float: right; margin-left: 5px;" type="submit" class="btn btn-primary">Test Connection</button>
                                <button style="margin-top: 20px; float: right; margin-left: 5px;" type="submit" class="btn btn-primary">Full Resync</button>
                                <button style="margin-top: 20px; float: right;" type="submit" class="btn btn-primary">Sync Now</button>
                            </div>
                        </form>
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