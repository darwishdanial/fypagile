import React from "react";
import { Link, usePage } from "@inertiajs/react";
import { CoordinatorSidebar } from "./CoordinatorSidebar";
import { User } from "lucide-react";
import { route } from 'ziggy-js';

export function CoordinatorLayout({ children }) {
    const { url } = usePage();
    return (
        <>
            <div className="flex h-screen">
                <CoordinatorSidebar ></CoordinatorSidebar>
                <div className="flex flex-col w-full">
                    <header className="bg-white shadow-md">
                        <div>
                            <nav className="flex items-center justify-between pt-2 px-4 w-full">
                                {/* Left Side: UTM Logo */}
                                <div className="flex-shrink-0">
                                    <img
                                        src="/images/utm-logo.png"
                                        alt="UTM Logo"
                                        className="w-25 mb-2"
                                    />
                                </div>

                                {/* Center: Navigation Links */}
                                <ul className="flex space-x-6 uppercase font-semibold text-3xl tracking-wide">
                                    <li>
                                        <Link
                                            href={route('coordinator.home')}
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p
                                                className={`${
                                                    url.startsWith(
                                                        "/Coordinator/Home"
                                                    )
                                                        ? "text-[#6D2323] border-b-3 border-[#6D2323]"
                                                        : "text-[#CCCCCC]"
                                                } inline-block m-0 pb-2`}
                                            >
                                                H
                                            </p>
                                        </Link>
                                    </li>
                                    <li>
                                        <Link
                                            href="/Coordinator/PSM1/list-students"
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p
                                                className={`${
                                                    url.startsWith(
                                                        "/Coordinator/PSM1"
                                                    )
                                                        ? "text-[#6D2323] border-b-3 border-[#6D2323]"
                                                        : "text-[#CCCCCC]"
                                                } inline-block m-0 pb-2`}
                                            >
                                                PSM1
                                            </p>
                                        </Link>
                                    </li>
                                    <li>
                                        <Link
                                            href="/Coordinator/PSM2/list-students"
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p
                                                className={`${
                                                    url.startsWith(
                                                        "/Coordinator/PSM2"
                                                    )
                                                        ? "text-[#6D2323] border-b-3 border-[#6D2323]"
                                                        : "text-[#CCCCCC]"
                                                } inline-block m-0 pb-2`}
                                            >
                                                PSM2
                                            </p>
                                        </Link>
                                    </li>
                                </ul>

                                <div className="flex-shrink-0 mb-2">
                                    <User />
                                </div>
                            </nav>
                        </div>
                    </header>

                    <main>{children}</main>
                </div>
            </div>
        </>
    );
}
