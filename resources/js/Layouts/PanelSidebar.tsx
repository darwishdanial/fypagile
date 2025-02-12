import React from "react";
import { Sidebar, Menu, MenuItem } from "react-pro-sidebar";
import { Link, usePage } from "@inertiajs/react";
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

    const menuItemsPSM1: MenuItemType[] = [
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: "/Coordinator/PSM1/grade-supervision",
        },
        {
            icon: <ListChecks />,
            label: "Grade Proposal",
            link: "/Coordinator/PSM1/grade-proposal",
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM1",
            link: "/Coordinator/PSM1/grade-PSM1",
        },
    ];

    const menuItemsPSM2: MenuItemType[] = [
        {
            icon: <ListChecks />,
            label: "Grade Supervision",
            link: "/Coordinator/PSM2/grade-supervision",
        },
        {
            icon: <ListChecks />,
            label: "Grade PSM2",
            link: "/Coordinator/PSM2/grade-PSM2",
        },
    ];

    let menuItems: MenuItemType[] = [];
    if (url === "/Coordinator/Home") {
        menuItems = []; 
    } else if (url.startsWith("/Coordinator/PSM1")) {
        menuItems = menuItemsPSM1; 
    } else if (url.startsWith("/Coordinator/PSM2")) {
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
                            <span className="pl-3 ">Coordinator</span>
                            <PanelRightOpen />
                        </div>
                    </MenuItem>

                    {/* Render menu items dynamically */}
                    {/* {menuItems.map(({ icon, label, link }, index) => (
                        
                        <MenuItem key={index} icon={icon}>
                            <Link
                                href={link}
                                style={{ textDecoration: "none" }}
                            >
                                <div className="!text-[#808080]">{label}</div>
                            </Link>
                        </MenuItem>
                    ))} */}

                    {menuItems.map(({ icon, label, link }, index) => {
                        // Determine if the menu item is active (current link)
                        const isActive = url === link;
                        const iconColor = isActive
                            ? "text-[#6D2323] font-bold"
                            : "text-[#808080]"; // Change color if active

                        return (
                            <MenuItem
                                key={index}
                                icon={icon}
                                className={iconColor}
                            >
                                <Link
                                    href={link}
                                    style={{ textDecoration: "none" }}
                                >
                                    <div className={iconColor}>{label}</div>
                                </Link>
                            </MenuItem>
                        );
                    })}

                    <MenuItem icon={<LogOut />}  className="border-t-2">Log Out</MenuItem>
                </Menu>
            </Sidebar>
        </div>
    );
}
