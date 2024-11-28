<section>
    <div class="flex items-center justify-between ">
        <div class="w-full p-3 bg-gradient-to-r from-blue-100 to-white flex flex-col items-center justify-center space-y-4">
            <div class="flex flex-col justify-center space-y-5 items-center">
                <h1 class="text-2xl font-bold text-blue-800">PROFILE</h1>
                <!-- Image Preview -->
                <div class="w-40 h-40 border rounded-full overflow-hidden flex justify-center items-center bg-gray-100">
                    <img id="image-preview" src="assets/friends-collaborating.jpg" alt="Preview" class="w-full h-full object-cover">
                </div>
                
                <!-- Buttons -->
                <div class="flex space-x-4">
                    <!-- Upload Image Button -->
                    <label for="upload-image" class="cursor-pointer bg-blue-800 text-white px-4 py-2 rounded-2xl">
                        Upload Image
                    </label>
                    <input id="upload-image" type="file" accept="image/*" class="hidden">
                    
                    <!-- Remove Image Button -->
                    <button id="remove-image" class="cursor-pointer bg-transparent border border-blue-800 text-blue-800 px-4 py-2 rounded-2xl" disabled>
                        Remove Image
                    </button>
                </div>
            </div>
            <div class="flex flex-col "> 
                <label for="" class="text-gray-500 text-sm">First name</label>
                <input type="text" name="" value="JUAN" id="" readonly class="bg-transparent  border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
            </div>
            <div class="flex flex-col ">
            <label for="" class="text-gray-500 text-sm">Last name</label>
                <input type="text" name="" value="SALAZAR" id="" readonly class="bg-transparent border-2 p-2 rounded-2xl border-blue-300 text-blue-800 font-bold">
            </div>
            <div class="flex flex-col ">
                <label for="" class="text-gray-500 text-sm">Middle name</label>
                <input type="text" name="" value="DELA CRUZ" id="" readonly class="bg-transparent border-2 p-2 rounded-2xl border-blue-300 text-blue-800 font-bold">
            </div>
        </div>
        <div class="w-full  h-full p-3">
        <button class="float-right p-2 text-blue-800 border-2 border-blue-800 w-24 rounded-3xl flex items-center justify-center" id="edit-button">
            <span><i class="fas fa-edit"></i></span>
            <span class="ml-2">Edit</span>
        </button>
            <div class="flex flex-col mt-5  space-y-4 ">
                <form action="" method="post" id="profile-form">

                <div >
                    <div class="flex items-center space-x-2 mb-2 mt-2">
                        <i class="fas fa-phone-alt text-white rounded-full bg-blue-800 p-2 text-md transform rotate-180"></i>
                        <span class="text-gray-500 text-sm">Contact Number</span>
                    </div>
                    <input type="text" name="" id="" value="+63912345789" readonly class="focus:outline-none bg-transparent w-80 border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                </div>
                <div>
                    <div class="flex items-center space-x-2 mb-2 mt-2">
                        <i class="fas fa-envelope text-white rounded-full bg-blue-800 p-2 text-md transform rotate-180"></i>
                        <span class="text-gray-500 text-sm">Email</span>
                    </div>
                    <input type="text" name="" id="" value="juan.delacruz@mail.com" readonly class="focus:outline-none bg-transparent w-80  border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                </div>
                <div>
                    <div class="flex items-center space-x-2 mb-2 mt-2">
                        <i class="fas fa-building text-white rounded-full bg-blue-800 p-2 text-md transform rotate-180"></i>
                        <span class="text-gray-500 text-sm">Organization</span>
                    </div>
                    <input type="text" name="" id="" value="System Development" readonly class="focus:outline-none bg-transparent w-80 border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                </div>
                <div class="flex flex-col space-y-2 mt-3">
                    <div class="flex items-center space-x-2 ">
                        <i class="fas fa-map-marker-alt text-white rounded-full bg-blue-800 p-2 text-md transform rotate-180"></i>
                        <span class="text-gray-500 text-sm">CURRENT ADDRESS</span>    
                    </div>
                  <div>
                    <div>
                        <span class="text-gray-500 text-sm">Province</span>
                    </div>
                      <input type="text" name="" id="" value="PANGASINAN" readonly class=" focus:outline-none bg-transparent w-80 border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                  </div>
                  <div>
                    <div>
                        <span class="text-gray-500 text-sm">City</span>

                    </div>
                      <input type="text" name="" id="" value="MAYOMBO" readonly class="focus:outline-none bg-transparent w-80 border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                  </div>
                </div>
                <div class="mt-4 border hidden" id="update-container">
                    <input type="submit" value="Update" class="rounded-3xl text-white w-full bg-blue-800 p-3">
                </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const uploadImageInput = document.getElementById("upload-image");
    const imagePreview = document.getElementById("image-preview");
    const removeImageButton = document.getElementById("remove-image");

    // Upload Image
    uploadImageInput.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove("hidden");
                removeImageButton.disabled = false; // Enable the Remove Image button
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove Image
    removeImageButton.addEventListener("click", () => {
        imagePreview.src = "assets/friends-collaborating.jpg"; // Reset to default image
        uploadImageInput.value = null;
        removeImageButton.disabled = true; // Disable the Remove Image button
    });

    const editButton = document.getElementById("edit-button");
    const updateContainer = document.getElementById("update-container");
    const formInputs = document.querySelectorAll("#profile-form input");

    editButton.addEventListener("click", (event) => {
        event.preventDefault();

        // Enable all inputs in the form
        formInputs.forEach(input => {
            input.removeAttribute("readonly");
        });

        // Show the Update button
        updateContainer.classList.remove("hidden");
    });
</script>
