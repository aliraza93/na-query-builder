<template>
    <div class="tab-pane active" id="interfaces" aria-labelledby="interfaces-tab" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Interface</h4>
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="interfaces">Interfaces</label>
                        <select id="interfaces" class="form-control">
                            <option value="eth2">eth2</option>
                               <option value="eth3" selected>eth3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch custom-control-inline">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" />
                            <label class="custom-control-label" for="customSwitch1">DHCP</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ipv4">IPV4</label>
                        <input type="text" id="ipv4" class="form-control" placeholder="CIDR Format, comma seperated">
                    </div>
                    <div class="form-group">
                        <button class="btn">Add Gateway</button>
                    </div>
                    <div class="form-group">
                        <button class="btn">Add Route</button>
                    </div>
                    <button style="float: right;" type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    
}
</script>