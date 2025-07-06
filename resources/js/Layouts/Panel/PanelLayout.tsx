import React, { useState } from "react";
import { Link, usePage, useForm } from "@inertiajs/react";
import { PanelSidebar } from "./PanelSidebar";
import { User } from "lucide-react";
import { route } from "ziggy-js";

export function PanelLayout({ children }) {
    const { url } = usePage();
    const { get } = useForm();
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);
    const currentSubpage = getCurrentSubpage(url);

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        get("/logout");
    };

    function getCurrentSubpage(url: string) {
        // Matches /Coordinator/PSM1/something or /Coordinator/PSM2/something
        const match = url.match(/\/Panel\/PSM[12]\/([^/?#]+)/i);
        return match ? match[1] : "grade-supervision";
    }

    return (
        <>
            <div className="flex h-screen">
                <PanelSidebar></PanelSidebar>
                <div className="flex flex-col w-full">
                    <header className="bg-white shadow-md">
                        <div>
                            <nav className="flex items-center justify-between px-4 w-full">
                                {/* Left Side: UTM Logo */}
                                <div className="flex-shrink-0">
                                    <img
                                        src="/images/utm-logo.png"
                                        alt="UTM Logo"
                                        className="w-25"
                                        title="UTM"
                                    />
                                </div>

                                {/* Center: Navigation Links */}
                                <ul className="flex uppercase font-semibold text-3xl tracking-wide">
                                    <li
                                        className={`${
                                            url.startsWith("/Panel/Home")
                                                ? "text-[#6D2323] border-b-3 border-[#6D2323]"
                                                : "text-[#CCCCCC]"
                                        } hover:bg-gray-100 p-2`}
                                    >
                                        <Link
                                            href={route("panel.home")}
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p>H</p>
                                        </Link>
                                    </li>
                                    <li
                                        className={`${
                                            url.startsWith("/Panel/PSM1")
                                                ? "text-[#6D2323] border-b-3 border-[#6D2323]"
                                                : "text-[#CCCCCC]"
                                        } hover:bg-gray-100 p-2`}
                                    >
                                        <Link
                                            href={`/Panel/PSM1/${currentSubpage}`}
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p>PSM1</p>
                                        </Link>
                                    </li>
                                    <li
                                        className={`${
                                            url.startsWith("/Panel/PSM2")
                                                ? "text-[#6D2323] border-b-3 border-[#6D2323]"
                                                : "text-[#CCCCCC]"
                                        } hover:bg-gray-100 p-2`}
                                    >
                                        <Link
                                            href={`/Panel/PSM2/${currentSubpage}`}
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p>PSM2</p>
                                        </Link>
                                    </li>
                                </ul>

                                <div className="relative">
                                    <button
                                        className="flex-shrink-0 hover:bg-gray-100 p-2 rounded-full"
                                        type="button"
                                        title="User Profile"
                                        onClick={() =>
                                            setIsDropdownOpen(!isDropdownOpen)
                                        }
                                    >
                                        <User />
                                    </button>

                                    {isDropdownOpen && (
                                        <div className="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                                            <button
                                                className="block w-full px-4 py-2 text-left hover:bg-gray-100"
                                                onClick={() =>
                                                    console.log("Go to Profile")
                                                }
                                            >
                                                Profile
                                            </button>
                                            <button
                                                className="block w-full px-4 py-2 text-left hover:bg-gray-100"
                                                 onClick={handleLogout}
                                            >
                                                Logout
                                            </button>
                                        </div>
                                    )}
                                </div>
                            </nav>
                        </div>
                    </header>

                    <main className="overflow-y-auto">{children}</main>
                </div>
            </div>
        </>
    );
}
