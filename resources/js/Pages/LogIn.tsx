import React from "react";
import { useForm, usePage  } from "@inertiajs/react";

interface Flash {
    error?: string;
}

export default function LoginPage() {
    const { data, setData, post, processing, errors } = useForm({
        username: "",
        password: "",
    });

    const { props } = usePage<{
        flash: Flash;
    }>();

    const flash = props.flash;


    const handleSubmit = (e) => {
        e.preventDefault();
        post("/validate_login"); // Send data to backend
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <div className="flex w-[900px] p-10">
                {/* Left Section - Logo & Text */}
                <div className="w-1/2 flex flex-col justify-center items-end mr-10">
                    <img
                        src="/images/utm-logo.png" // Change to actual image path
                        alt="UTM Logo"
                        className="w-517"
                    />
                    <p className="text-2xl font-bold mt-2 italic leading-none mb-0">
                        <span className="text-[#A31D1D]">FYP</span> Management
                        System
                    </p>
                    <p className="text-sm italic text-[#A31D1D] leading-none mt-1 ">
                        + smart Panel Assignment
                    </p>
                </div>

                {/* Right Section - Login Form */}
                <div className="w-1/2 flex flex-col justify-center  bg-white p-2 rounded-sm shadow-md border border-gray-200">

                    <form onSubmit={handleSubmit}>
                        <div className="mb-4">
                            <input
                                type="text"
                                name="username"
                                value={data.username}
                                onChange={(e) =>
                                    setData("username", e.target.value)
                                }
                                placeholder="Username"
                                className="w-full px-4 py-2 bg-gray-100 rounded-sm focus:outline-none focus:ring-2 focus:ring-[#730000]"
                            />
                                                    {errors.username && (
                                <p className="text-red-500  mb-4">{errors.username}</p>
                            )}
                        </div>

                        <div className="mb-4">
                            <input
                                type="password"
                                name="password"
                                value={data.password}
                                onChange={(e) =>
                                    setData("password", e.target.value)
                                }
                                placeholder="Password"
                                className="w-full px-4 py-2  bg-gray-100 rounded-sm focus:outline-none focus:ring-2 focus:ring-[#730000]"
                            />
                                                    {errors.password && (
                                <p className="text-red-500 ">{errors.password}</p>
                            )}
                        </div>

                        {flash?.error && (
                            <p className="text-red-500 text-sm text-center bg-red-100 , p-2 mb-4 rounded-sm">
                                {flash.error}
                            </p>
                        )}
    
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#730000] text-white py-2 border !rounded-sm hover:bg-[#5a0000] transition font-semibold"
                        >
                            {processing ? "Logging in..." : "Log In"}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    );
}
