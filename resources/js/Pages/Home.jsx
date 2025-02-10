export default function Home() {
    return (
      <div className="min-h-screen bg-gray-100 py-10 px-5">
        <div className="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
          <h1 className="font-bold text-3xl text-center text-blue-600 mb-4">
            Hello Inertia React
          </h1>
  
          <h3 className="text-xl text-gray-700 font-semibold mb-2">
            Beloved Manhattan Soup Stand Closes
          </h3>
          <p className="font-bold text-red-400  mb-4">
            New Yorkers are facing the winter chill with one less comforting option. The beloved soup stand, which became an iconic winter spot, has officially closed its doors. The decision has left many fans heartbroken, especially those who relied on it during the colder months.
          </p>
  
          <button className="mt-4 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
            Learn More
          </button>
        </div>
      </div>
    );
  }
  