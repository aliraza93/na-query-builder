<template>
    <div>
        <!-- User Card & Plan Starts -->
        <div class="row">
            <!-- User Card starts-->
            <div class="col-xl-4 col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic information</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="policy_name">Policy Name</label>
                                <input type="text" id="policy_name" :value="policy.policy_name" disabled class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="block_page">Block Page</label>
                                <select id="block_page" class="form-control" disabled>
                                    <option selected disabled>{{ blockpage.title }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <input type="text" id="priority" :value="policy.priority" disabled class="form-control">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon starts -->
            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Policy Details</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="rules-tab" data-toggle="tab" href="#rules" aria-controls="rules" role="tab" aria-selected="true"><i data-feather="folder"></i> Rules</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="scope-tab" data-toggle="tab" href="#scope" aria-controls="scope" role="tab" aria-selected="false"><i data-feather="users"></i> Scope</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <rules-tab></rules-tab>
                            <scope-tab></scope-tab>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
            <!-- /User Card Ends-->
        </div>
    </div>
</template>

<script>
import RulesTab from './Tabs/RulesTab.vue'
import ScopeTab from './Tabs/ScopeTab.vue'

export default {
    components: { RulesTab, ScopeTab },
    props: ['policy', 'blockpage'],

    created() {
        var _this = this;
        this.get_policy_details();
        // EventBus.$on("policies-added", function() {
        //     _this.get_policies();
        // });
    },

    methods: {
        get_policy_details() {
            
        }
    }

}
</script>