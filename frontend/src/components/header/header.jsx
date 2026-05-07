import NavBar from 'react-bootstrap/Navbar'
import Container from 'react-bootstrap/Container';
import Button from 'react-bootstrap/Button';
import Stack from 'react-bootstrap/Stack';
import { useNavigate } from "react-router";



function Header () {
    const navigate = useNavigate();
    return (
        <NavBar className='bg-body-tertiary' expand='lg'>
            <Container fluid>
            <NavBar.Brand className='me-auto'>Senac Oficinas</NavBar.Brand>
            <Stack gap={3} direction='horizontal'>

            <Button onClick={() => navigate('/participantes')}>Participantes</Button>
            <Button onClick={() => navigate('/instrutores')}>Instrutores</Button>
            <Button onClick={() => navigate('/oficinas')}>Oficinas</Button>
            <Button onClick={() => navigate('/inscricoes')}>Inscrições</Button>
            </Stack>

            </Container>
        </NavBar>
    )
}

export default Header