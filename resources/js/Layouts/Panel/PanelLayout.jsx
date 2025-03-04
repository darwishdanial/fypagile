import React from "react";
import { Link, usePage } from "@inertiajs/react";
import { PanelSidebar } from "./PanelSidebar";

export function PanelLayout({ children }) {
    const { url } = usePage();
    return (
        <>
            <div className="flex h-screen overflow-hidden">
                <PanelSidebar></PanelSidebar>
                <div className="flex flex-col w-full">
                    <header className="bg-white shadow-md">
                        <div>
                            <nav className="flex items-center justify-center pt-2">
                                {/* Navbar Links */}
                                <ul className="hidden lg:flex space-x-4 uppercase font-semibold text-3xl tracking-wide mb-0 ">
                                    <li>
                                        <Link
                                            href="/Panel/Home"
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p
                                                className={`${
                                                    url.startsWith(
                                                        "/Panel/Home"
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
                                            href="/Panel/PSM1/grade-supervision"
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p
                                                className={`${
                                                    url.startsWith(
                                                        "/Panel/PSM1"
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
                                            href="/Panel/PSM2/grade-supervision"
                                            style={{ textDecoration: "none" }}
                                        >
                                            <p
                                                className={`${
                                                    url.startsWith(
                                                        "/Panel/PSM2"
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
                            </nav>
                        </div>
                    </header>

                    <main>{children}</main>
                </div>
            </div>
        </>
    );
}
