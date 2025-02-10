import React from "react";
import { Link, usePage } from "@inertiajs/react";

export function CoordinatorLayout({ children }) {
    const { url } = usePage(); // Get current URL for active link styling

    return (
        <>
            <header className="bg-white shadow-md">
                <div>
                    <nav className="flex items-center justify-center pt-2 ">
                        {/* Mobile Menu Icon */}
                        <div className="absolute left-2">
                            <button className="text-black text-2xl">
                                &#9776;
                            </button>
                        </div>

                        {/* Navbar Links */}
                        <ul className="hidden lg:flex space-x-4 uppercase font-semibold text-3xl tracking-wide mb-0">
                            <li>
                                <Link
                                    href="/"
                                    style={{ textDecoration: "none" }}
                                >
                                    <p
                                        className={`${
                                            url === "/"
                                                ? "text-[#6D2323] border-b-2 border-[#6D2323]"
                                                : "text-[#CCCCCC]"
                                        } inline-block m-0`}
                                    >
                                        H
                                    </p>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/PSM1"
                                    style={{ textDecoration: "none" }}
                                >
                                    <p
                                        className={`${
                                            url === "/PSM1"
                                                ? "text-[#6D2323] border-b-2 border-[#6D2323]"
                                                : "text-[#CCCCCC]"
                                        } inline-block m-0`}
                                    >
                                        PSM1
                                    </p>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/PSM2"
                                    style={{ textDecoration: "none" }}
                                >
                                    <p
                                        className={`${
                                            url === "/PSM2"
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
        </>
    );
}
