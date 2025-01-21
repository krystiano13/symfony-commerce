import { useState } from "react";
import { Card } from "primereact/card";
import { Button } from "primereact/button";
import styled, { css } from "styled-components";

const NavbarButtons = styled.section`
    display: flex;
    align-items: center;
    gap: .5rem;
    
    @media screen and (max-width: 1023px) {
        display: none;
    }
`;

const MobileButtons = styled.div`
    position: fixed;
    width: 100%;
    
    transition: transform 250ms;
    will-change: transform;
    
    ${(props) => props.open && css`
        transform: translateY(25%);
    `}

    ${(props) => !props.open && css`
        transform: translateY(-100%);
    `}
    
    .p-card-content {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        gap: .5rem;
        padding-top: 50%;
        align-items: center;
        margin-right: 1.25rem;
        margin-left: 1.25rem;
        
        a {
            width: 100%;
            text-decoration: none;
            
            button {
                width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
            }
        }
    }
    
    @media screen and (min-width: 1023px) {
        display: none;
    }
`;

const HamburgerButtonWrapper = styled.div`
    @media screen and (min-width: 1023px) {
        display: none;
    }
`;

export function Navbar({ user }) {
    const Buttons = () => (
        <>
            {
                (user.email == "" || user.id == -1) &&
                <>
                    <a href="/login">
                        <Button>Logowanie</Button>
                    </a>
                    <a href="/register">
                        <Button severity="secondary">Rejestracja</Button>
                    </a>
                </>
            }
            {
                user.email && user.id &&
                <>
                    <h5>{user.email}</h5>
                    {
                        user.email === "admin@admin.com" &&
                        <a href="/admin">
                            <Button severity="contrast">Panel Admina</Button>
                        </a>
                    }
                    <a href="/cart">
                        <Button severity="secondary">Koszyk</Button>
                    </a>
                    <a href="/logout">
                        <Button>Wyloguj się</Button>
                    </a>
                </>
            }
        </>
    )

    const [open, setOpen] = useState(false);

    return (
        <Card
            className="p-card-navbar"
            style={{
                padding: "0rem",
                position: "sticky",
                top: "0px",
                zIndex: "100"
            }}
        >
            <a style={{ textDecoration: "none", color: "#4b5563" }} href="/">
                <h4>
                    E-Commerce
                </h4>
            </a>
            <MobileButtons open={open}>
                <Card>
                    <Buttons />
                </Card>
            </MobileButtons>
            <HamburgerButtonWrapper>
                <Button
                    onClick={() => setOpen(prev => !prev)}
                    style={{
                        width: "3rem",
                        height: "3rem",
                        display: "flex",
                        justifyContent: "center"
                    }}
                >
                    <i className="pi pi-bars"></i>
                </Button>
            </HamburgerButtonWrapper>
            <NavbarButtons>
                <Buttons />
            </NavbarButtons>
        </Card>
    )
}