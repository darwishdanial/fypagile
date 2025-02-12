import React from "react";
import { Link, usePage } from "@inertiajs/react";
import { CoordinatorSidebar } from "./CoordinatorSidebar";

export function CoordinatorLayout({ children }) {
    const { url } = usePage();
    return (
        <>
            <div className="flex h-screen overflow-hidden">
                <CoordinatorSidebar></CoordinatorSidebar>
                <div className="flex flex-col w-full">
                    <header className="bg-white shadow-md">
                        <div>
                            <nav className="flex items-center justify-center pt-2">
                                {/* Navbar Links */}
                                <ul className="hidden lg:flex space-x-4 uppercase font-semibold text-3xl tracking-wide mb-0 ">
                                    <li>
                                        <Link
                                            href="/Coordinator/Home"
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p
                                                className={`${
                                                    url.startsWith(
                                                        "/Coordinator/Home"
                                                    )
                                                        ? "text-[#6D2323] border-b-2 border-[#6D2323]"
                                                        : "text-[#CCCCCC]"
                                                } inline-block m-0 `}
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
                                                        ? "text-[#6D2323] border-b-2 border-[#6D2323]"
                                                        : "text-[#CCCCCC]"
                                                } inline-block m-0 `}
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
                                                        ? "text-[#6D2323] border-b-2 border-[#6D2323]"
                                                        : "text-[#CCCCCC]"
                                                } inline-block m-0`}
                                            >
                                                PSM2
                                            </p>
                                        </Link>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </header>

                    <main>{children}</main>
                </div>
            </div>
        </>
    );
}
