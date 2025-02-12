import React from "react";

export default function LoginPage() {
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
                            <span className="text-[#A31D1D]">FYP</span>{" "}
                            Management System
                        </p>
                        <p className="text-sm italic text-[#A31D1D] leading-none mt-1 ">
                            enhance with smart Panel Assignment
                        </p>
                </div>

                {/* Right Section - Login Form */}
                <div className="w-1/2 flex flex-col justify-center border bg-white p-2 rounded-sm shadow-md">
                    <input
                        type="text"
                        placeholder="Username"
                        className="w-full px-4 py-2 mb-4 border rounded-sm focus:outline-none focus:ring-2 focus:ring-[#730000]"
                    />
                    <input
                        type="password"
                        placeholder="Password"
                        className="w-full px-4 py-2 mb-4 border rounded-sm focus:outline-none focus:ring-2 focus:ring-[#730000]"
                    />
                    <button className="w-full bg-[#730000] text-white py-2 border !rounded-sm hover:bg-[#5a0000] transition font-semibold">
                        Log In
                    </button>
                </div>
            </div>
        </div>
    );
}
