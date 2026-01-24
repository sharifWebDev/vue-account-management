<script setup>
</script>

<template>
      <div  class="skelton_loader" >
        <div class="row">
            <div class="col-lg-12">
                <div class="card is-loading">
                    <div class="content p-3 d-flex justify-content-between">
                        <div style="width: 31%;">
                            <p style="height: 15px; margin-bottom: 10px; width: 100px;"></p>
                            <h2 style="height: 40px;"></h2>
                        </div>
                        <div style="width: 31%;">
                            <p style="height: 15px; margin-bottom: 10px; width: 100px;"></p>
                            <h2 style="height: 40px"></h2>
                        </div>
                        <div style="width: 31%;">
                            <p style="height: 15px; margin-bottom: 10px; width: 100px;"></p>
                            <h2 style="height: 40px"></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8  mt-4">
                <div class="skelton_loader is-loading">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mt-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 8px;"></h2>
                                        </div>
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 8px;"></h2>
                                        </div>
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 8px;"></h2>
                                        </div>
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 8px;"></h2>
                                        </div>
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 8px;"></h2>
                                        </div>
                                        <div class="col-lg-2 d-flex justify-content-center">
                                            <h2 style="height: 15px; margin: 8px; width: 100px;"></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-0">
                                    <div class="row border-bottom" v-for="r in 6" :key="r">
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 15px;"></h2>
                                        </div>
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 15px;"></h2>
                                        </div>
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 15px;"></h2>
                                        </div>
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 15px;"></h2>
                                        </div>
                                        <div class="col-lg-2">
                                            <h2 style="height: 15px; margin: 15px;"></h2>
                                        </div>
                                        <div class="col-lg-2 d-flex justify-content-center align-items-center">
                                            <h2 style="height: 20px; margin: 4px; width: 25px;"></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mt-4 is-loading">
                    <div class="content p-3">
                        <h2 style="height: 100px; margin-bottom: 240px; "></h2>
                        <div class="d-flex justify-content-end">
                            <h2 style="height: 40px; margin-bottom: 10px; width: 80px; margin-right: 10px;"></h2>
                            <h2 style="height: 40px; margin-bottom: 10px; width: 80px;"></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>