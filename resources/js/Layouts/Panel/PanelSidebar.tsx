import React from "react";
import { Sidebar, Menu, MenuItem } from "react-pro-sidebar";
import { Link, usePage, useForm } from "@inertiajs/react";
import {
    PanelRightClose,
    PanelRightOpen,
    ListChecks,
    LogOut,
} from "lucide-react";

interface MenuItemType {
    icon: React.ReactNode;
    label: string;
    link: string;
}

export function PanelSidebar() {
    const [collapsed, setCollapsed] = React.useState(false);
    const { url } = usePage();
    const { get } = useForm();

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        get("/logout");
    };

    const menuItemsPSM1: MenuItemType[] = [
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: "/Panel/PSM1/grade-supervision",
        },
        {
            icon: <ListChecks />,
            label: "Grade Proposal",
            link: "/Panel/PSM1/grade-proposal",
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM1",
            link: "/Panel/PSM1/grade-PSM1",
        },
    ];

    const menuItemsPSM2: MenuItemType[] = [
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: "/Panel/PSM2/grade-supervision",
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM2",
            link: "/Panel/PSM2/grade-PSM2",
        },
    ];

    let menuItems: MenuItemType[] = [];
    if (url === "/Panel/Home") {
        menuItems = [];
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
                        const isActive = url === link;
                        const iconColor = isActive
                            ? "text-[#6D2323] font-bold"
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
