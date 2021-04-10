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
                                                <h4 class="mb-0">{{ user.user_name }}</h4>
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
                                            <span class="card-text user-info-title font-weight-bold mb-0">Display Name</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.user_name }}</p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title">
                                            <i data-feather="user" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Distinguished Name</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.user_name }}</p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title">
                                            <i data-feather="user" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Name</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.user_name }}</p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title">
                                            <i data-feather="mail" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Email</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.email }}</p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title">
                                            <i data-feather="calendar" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">When Created</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.when_created }}</p>
                                    </div>
                                    <div class="d-flex flex-wrap">
                                        <div class="user-info-title">
                                            <i data-feather="calendar" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">When Changed</span>
                                        </div>
                                        <p style="margin-left: auto;" class="card-text mb-0">{{ user.when_updated }}</p>
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
                        <h4 class="card-title">User Details</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="containers-tab" data-toggle="tab" href="#containers" aria-controls="containers" role="tab" aria-selected="true"><i data-feather="folder"></i> Containers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ous-tab" data-toggle="tab" href="#ous" aria-controls="ous" role="tab" aria-selected="false"><i data-feather="tool"></i> OUS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="groups-tab" data-toggle="tab" href="#groups" aria-controls="groups" role="tab" aria-selected="false"><i data-feather="users"></i> Groups</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ips-observed-tab" data-toggle="tab" href="#ips-observed" aria-controls="ips-observed" role="tab" aria-selected="false"><i data-feather="tool"></i> IPS Observed</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="policy-tab" data-toggle="tab" href="#policy" aria-controls="policy" role="tab" aria-selected="false"><i class="fas fa-file-powerpoint"></i> Policy</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <containers-tab></containers-tab>
                            <ous-tab></ous-tab>
                            <groups-tab></groups-tab>
                            <ips-observed></ips-observed>
                            <policy-tab></policy-tab>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
        </div>
    </div>
</template>

<script>
import ContainersTab from './Tabs/ContainersTab.vue'
import GroupsTab from './Tabs/GroupsTab.vue'
import OusTab from './Tabs/OusTab.vue'
import IpsObserved from './Tabs/IpsObserved'
import PolicyTab from './Tabs/PolicyTab'

export default {
    components: { ContainersTab, OusTab, GroupsTab, IpsObserved, PolicyTab },
    props: ['user']
}
</script>