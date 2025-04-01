import React from "react";
import { Sidebar, Menu, MenuItem } from "react-pro-sidebar";
import { Link, usePage, useForm } from "@inertiajs/react";
import {
    PanelRightClose,
    PanelRightOpen,
    ListChecks,
    LogOut,
    House
} from "lucide-react";

interface MenuItemType {
    icon: React.ReactNode;
    label: string;
    link: string;
}
import { route } from 'ziggy-js';

export function PanelSidebar() {
    const [collapsed, setCollapsed] = React.useState(false);
    const { url } = usePage();
    const { get } = useForm();

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        get("/logout");
    };

    const menuItemsHome: MenuItemType[] = [
        {
            icon: <House />,
            label: "Dashobard",
            link: route('panel.home'),
        },
    ];

    const menuItemsPSM1: MenuItemType[] = [
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: route('panel.PSM1.gradeSupervision'),
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM1",
            link: route('panel.PSM1.gradePSM1'),
        },
    ];

    const menuItemsPSM2: MenuItemType[] = [
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: route('panel.PSM2.gradeSupervision'),
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM2",
            link: route('panel.PSM2.gradePSM2'),
        },
    ];

    let menuItems: MenuItemType[] = [];
    if (url.startsWith("/Panel/Home")) {
        menuItems = menuItemsHome;
    } else if (url.startsWith("/Panel/PSM1")) {
        menuItems = menuItemsPSM1;
    } else if (url.startsWith("/Panel/PSM2")) {
        menuItems = menuItemsPSM2;
    }

    return (
        <div className="flex h-full !text-[#808080] bg-[#FFFFFF]">
            <Sidebar collapsed={collapsed} backgroundColor="#FFFFFF">
                <Menu>
                    <MenuItem
                        onClick={() => setCollapsed(!collapsed)}
                        icon={collapsed && <PanelRightClose />}
                    >
                        <div className="flex justify-between">
                            <span className="pl-3 ">Panel</span>
                            <PanelRightOpen />
                        </div>
                    </MenuItem>

                    {menuItems.map(({ icon, label, link }, index) => {
                        const isActive = url === new URL(link, window.location.origin).pathname;
                        const iconColor = isActive
                            ? "text-[#6D2323] font-semibold bg-gray-100"
                            : "text-[#808080]";

                        return (
                            <MenuItem
                                key={index}
                                icon={icon}
                                className={iconColor}
                                component="div"
                            >
                                <Link
                                    href={link}
                                    style={{
                                        textDecoration: "none",
                                        color: "inherit",
                                    }}
                                >
                                    {label}
                                </Link>
                            </MenuItem>
                        );
                    })}

                    <form onSubmit={handleLogout} className="w-full">
                        <MenuItem icon={<LogOut />}>
                            <button type="submit" className="w-full text-left">
                                Log Out
                            </button>
                        </MenuItem>
                    </form>
                </Menu>
            </Sidebar>
        </div>
    );
}
