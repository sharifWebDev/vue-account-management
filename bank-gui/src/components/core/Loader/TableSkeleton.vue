<template>
    <div class="row justify-content-center" >
    <div class="col-lg-12">
      <div class="skelton_loader is-loading">
        <div class="row">
          <div class="col-lg-12">
            <h2 style="height: 30px; margin: 12px 3px; width: 13%; border: 1px solid #c7c7c7;"></h2>
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
                <div class="row border-bottom" v-for="r in 7" :key="r">
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
  </div>
</template>