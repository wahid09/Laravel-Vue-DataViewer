<template>
    <div id="user">
        <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">User List</div>

                <div class="card-body">
                    <div class="table-resposive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in users" :key="item.id">
                                    <td scope="row">{{index+1}}</td>
                                    <td>{{item.name}}</td>
                                    <td>{{item.email}}</td>
                                    <td>{{item.phone}}</td>
                                    <td>{{item.Country}}</td>
                                    <td v-if="item.status == 1">
                                        <span class="">Active</span>
                                    </td>
                                    <td v-else>
                                        <span class="">Inactive</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                        <a href="" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <vue-progress-bar></vue-progress-bar>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                users: []
            }
        },
        mounted() {
            //console.log('Component mounted.')
            this.getData();
        },
        methods:{
            getData(){
                this.$Progress.start()
                axios.get('/api/user-profile')
                .then(response =>{
                    this.users = response.data.data
                    this.$Progress.finish()
                })
                .catch(e =>{
                    console.log(e)
                    this.$Progress.fail()
                })
            }
        }
    }
</script>
