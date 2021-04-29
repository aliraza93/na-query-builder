<template>
    <div class="tab-pane" id="scope" style="margin-top: -13px;" aria-labelledby="scope-tab" role="tabpanel">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" aria-controls="users" role="tab" aria-selected="true"><i data-feather="folder"></i> Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="computers-tab" data-toggle="tab" href="#computers" aria-controls="computers" role="tab" aria-selected="false"><i data-feather="users"></i> Computers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="subnet-tab" data-toggle="tab" href="#subnet" aria-controls="subnet" role="tab" aria-selected="true"><i data-feather="folder"></i> Subnets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="groups-tab" data-toggle="tab" href="#groups" aria-controls="groups" role="tab" aria-selected="false"><i data-feather="users"></i> Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="containers-tab" data-toggle="tab" href="#containers" aria-controls="containers" role="tab" aria-selected="true"><i data-feather="folder"></i> Containers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ou-tab" data-toggle="tab" href="#ou" aria-controls="ou" role="tab" aria-selected="false"><i data-feather="users"></i> OUS</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <users-tab :policy="policy"></users-tab>
                    <computers-tab :policy="policy"></computers-tab>
                    <groups-tab :policy="policy"></groups-tab>
                    <containers-tab :policy="policy"></containers-tab>
                    <ou-tab :policy="policy"></ou-tab>
                    <subnets-tab :policy="policy"></subnets-tab>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import GroupsTab from './Tabs/GroupsTab.vue'
import ComputersTab from './Tabs/ComputersTab.vue'
import UsersTab from './Tabs/UsersTab.vue'
import ContainersTab from './Tabs/ContainersTab.vue'
import OuTab from './Tabs/OuTab.vue'
import SubnetsTab from './Tabs/SubnetsTab.vue'
export default {
  components: { UsersTab, ComputersTab, GroupsTab, ContainersTab, OuTab, SubnetsTab},
  props: ['policy'],
    
}
</script>