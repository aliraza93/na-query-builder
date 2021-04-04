<template>
    <div>
        <!-- User Card & Plan Starts -->
        <div class="row">
            <!-- User Card starts-->
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card user-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 d-flex flex-column justify-content-between border-container-lg">
                                <div class="user-avatar-section">
                                    <div class="d-flex justify-content-start">
                                        <img class="img-fluid rounded" src='/images/avatars/7.png' height="104" width="104" alt="User avatar" />
                                        <div class="d-flex flex-column ml-1">
                                            <div class="user-info mb-1">
                                                <h4 class="mb-0">{{ user.name }}</h4>
                                                <span class="card-text">{{ user.email }}</span>
                                            </div>
                                            <div class="d-flex flex-wrap">
                                                <a href="app/user/edit" class="btn btn-primary">Edit</a>
                                                <button class="btn btn-outline-danger ml-1">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-2 mt-xl-0">
                                <div class="user-info-wrapper">
                                    <div class="d-flex flex-wrap">
                                        <div class="user-info-title">
                                            <i data-feather="user" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Policy Name</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.name }}</p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title">
                                            <i data-feather="user" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Block Page</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.name }}</p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title">
                                            <i data-feather="user" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Priority</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.id }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Card Ends-->
        </div>
        <div class="row">
            <!-- Tabs with Icon starts -->
            <div class="col-xl-12 col-lg-12">
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
        </div>
    </div>
</template>

<script>
import RulesTab from './Tabs/RulesTab.vue'
import ScopeTab from './Tabs/ScopeTab.vue'

export default {
    components: { RulesTab, ScopeTab },
    props: ['user']
}
</script>