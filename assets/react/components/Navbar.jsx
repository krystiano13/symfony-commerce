import { Card } from "primereact/card";
import { Button } from "primereact/button";

export function Navbar({ user }) {
    return (
        <Card
            className="p-card-navbar"
            style={{
                padding: "0rem"
            }}
        >
            <a style={{ textDecoration: "none", color: "#4b5563" }} href="/">
                <h4
                    style={{
                        margin: 0,
                        padding: 0
                    }}
                >
                    E-Commerce
                </h4>
            </a>
            <section
                style={{
                    display: "flex",
                    alignItems: "center",
                    gap: ".5rem"
                }}
            >
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
                        <h5>{ user.email }</h5>
                        <a href="/logout">
                            <Button>Wyloguj się</Button>
                        </a>
                    </>
                }
            </section>
        </Card>
    )
}