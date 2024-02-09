<div class="flex flex-col dark:text-white md:mb-32">
    <div class="block md:flex flex-1 flex-wrap">
        <div class="flex-1 bg-neutral-300 dark:bg-slate-700 border-1 p-8 h-screen" style="background-image: url('{{ glide :src="left_background" width="1920" height="1080" }}')">
            <div class="w-full lg:w-3/4 mx-auto">
                <div class="text-left h-32"></div>
                <div class="text-left lg:mt-16 h-20">
                    <h1 class="text-2xl font-bold">Account Login</h1>
                </div>

                <div class="mb-6">
                    I already have an account
                </div>
                <form method="POST" action="/login" class="bg-white dark:bg-slate-500 shadow-md p-8 text-gray-700 dark:text-white">
                    <?php
                    if(isset($errors) && $errors->any()) {
                        echo '<div class="rounded-sm bg-red-100 p-2 mb-4 text-red-400 text-sm"><ul>';
                        if ($errors->any()) {
                            foreach($errors->all() as $error) {
                                echo '<li>'.$error.'</li>';
                            }
                        }
                        echo '</ul></div>';
                    }
                    ?>

                    <input type="hidden" name="_token" value="{{ csrf_token }}">
                    <div class="mb-4">
                        <label for="email" class="block  text-sm font-semibold mb-2">Email *</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" class="w-full text-black px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-sm focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300" />
                    </div>
                    <div class="mb-1">
                        <label for="password" class="block text-sm font-semibold mb-2">Password *</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" class="w-full text-black px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-sm focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300" />
                    </div>
                    <div class="mb-4">
                        <a href="#" class="text-sm hover:text-gray-600 dark:hover:text-neutral-300 hover:underline">Forgot your password?</a>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="w-1/3 px-2 py-3 text-white bg-black rounded-full hover:bg-gray-900 focus:outline-none text-sm focus:ring focus:ring-gray-200 focus:ring-opacity-50">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex-1 p-8 border-gray-100 h-screen" style="background-image: url('{{ glide :src="right_background" width="1920" height="1080" }}')">
            <div class="w-full lg:w-3/4 mx-auto">
                <div class="text-left h-16"></div>
                <div class="text-left lg:mt-16 h-20"></div>

                <div class="mb-6">
                    I don't have an account
                </div>
                <div class="text-sm mb-4">
                    Get the most out of your experience and enjoy more perks with a personal account.
                </div>
                <div class="bg-gray-100 dark:bg-slate-600 shadow-md p-6 md:p-8 mb-8">
                    <div class="md:px-6">
                        <div class="text-left mb-4">
                            <p class="text-gray-700 dark:text-white text-sm">What you'll find in your account.</p>
                        </div>
                        <div class="text-left mb-4">
                            <ul class="list-unstyled text-gray-700 dark:text-white text-sm">
                                <li class="border-b py-3 flex items-center gap-8">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-check-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0m-.646 5.354a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
                                        </svg>
                                    </div>
                                    <span>Save your billing and delivery information to order faster</span>
                                </li>
                                <li class="border-b py-3 flex items-center gap-8">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-heart-fill" viewBox="0 0 16 16">
                                            <path d="M2 15.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2zM8 4.41c1.387-1.425 4.854 1.07 0 4.277C3.146 5.48 6.613 2.986 8 4.412z"/>
                                        </svg>
                                    </div>
                                    <span>Access your session & order history</span>
                                </li>
                                <li class="border-b py-3 flex items-center gap-8">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                                        </svg>
                                    </div>
                                    <span>Receive the latest news and updates on our promotional or educational materials</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="w-full min-w-[205px] lg:w-1/3 btn btn-wireframe text-sm border-2 rounded-full p-3 border-black dark:border-white">Request an account</button>
                </div>
            </div>
        </div>
    </div>
</div>
